<?php
include "includes/utils.php";
include "includes/db.php";

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
}

function findProductById($products, $id) {
    foreach ($products as $product) {
        if ($product['product_id'] == $id) {
            return $product;
        }
    }
    return null; // 如果未找到，返回null
}

// 从 cookie 中读取购物车数据
$checkout = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
$total = 0;
$checkout_html = ''; // 修正变量名

$sql = 'SELECT * FROM Product 
        INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
        INNER JOIN PriceCut ON PriceCut.product_id = Product.product_id';
$stmt = $conn->prepare($sql);
try {
    $stmt->execute();
} catch(Exception $e) {
    create_error_page("Errore nel database");
}
$result = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

foreach ($checkout as $item) {
    $id = $item['product_id'] ?? null;
    if (!$id) {
        continue;
    }

    $product = findProductById($products, $id);
    if ($product) {
        $price_per_item = $product['new_price'] ?? $product['price'];
        $price = $price_per_item * $item['quantity'];
        $total += $price;
        $checkout_html .= render(file_get_contents('template/checkout.item.html'), [
            'name' => $product['product_name'],
            'price' => $price,
        ]);
    }
}
// create order
if($total <= 0) {
    create_error_page("Il carrello è vuoto");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = round($total, 2);
    $user_id = json_decode($_COOKIE['user'], true)['user_id'];
    // $order_datetime = date('Y-m-d H:i:s');
    // echo $order_datetime;
    $status = 1;

    $sql = "INSERT INTO `Orders` (total, status, user_id) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param('dis', $total, $status, $user_id);
    $stmt->execute();
    if ($stmt->error) {
        create_error_page("Errore nel database");
        exit();
    }
    $order_id = $stmt->insert_id;
    $stmt->close();

    foreach ($checkout as $item) {
        $id = $item['product_id'] ?? null;
        if (!$id) {
            continue;
        }

        $product = findProductById($products, $id);
        if ($product) {
            $quantity = $item['quantity'];

            $sql = "INSERT INTO OrderDetail (quantity, order_id, product_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $quantity, $order_id, $id);
            $stmt->execute();
            if ($stmt->error) {
                create_error_page("Errore nel database");
                exit();
            }
            $stmt->close();
        }
    }
    // empty cart
    setcookie('cart', json_encode([]), time() - 3600);
    header("Location: productpaid.php");
    exit();
}



echo create_page('template/index.html', [
    'header_title' => "Checkout | MediaShop2000",
    'header_description' => "la pagina di checkout del sito MediaShop2000",
    'header_keywords' => "checkout, Shop, media, games",
    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/checkout.html'), [
        "items" => $checkout_html,
        "total" => round($total, 2),
    ]),
    'page_footer' => create_page_footer(),
]);
?>
