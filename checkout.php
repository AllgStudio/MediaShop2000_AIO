<?php
include "includes/utils.php";
include "includes/db.php";

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
}

function findProductById($products, $id) {
    foreach ($products as $product) {
        if ($product['p_id'] == $id) {
            return $product;
        }
    }
    return null; // 如果未找到，返回null
}

// 从 cookie 中读取购物车数据
$checkout = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
$total = 0;
$checkout_html = ''; // 修正变量名
try {
    $sql = 'SELECT Product.product_id as p_id, Product.product_name, Product.brand, Product.price, ProductImage.url, Category.category_name
            FROM Product 
            Left JOIN ProductImage ON Product.product_id = ProductImage.product_id
            LEFT JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            left JOIN Category ON CategoryProduct.category_id = Category.category_id';
    $stmt = $conn->prepare($sql);

    $stmt->execute();

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
                'quantity' => $item['quantity'],
                'color' => $item['color'],
            ]);
        }
    }
} catch(Exception $e) {
    create_error_page("Errore nel database");
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
    
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $card = $_POST['card'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $note = $_POST['message'] ?? '';


    if (!$name || !$surname || !$email || !$phone || !$address || !$city || !$zip || !$card || !$expiry || !$cvv) {
        create_error_page("Compila tutti i campi");
        exit();
    }

    // check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        create_error_page("Email non valida");
        exit();
    }

    // check phone
    if (!preg_match('/^\+?[0-9]{6,}$/', $phone)) {
        create_error_page("Numero di telefono non valido");
        exit();
    }

    // check zip
    if (!preg_match('/^[0-9]{5}$/', $zip)) {
        create_error_page("CAP non valido");
        exit();
    }

    // check card
    if (!preg_match('/^[0-9]{16}$/', $card)) {
        create_error_page("Numero di carta non valido");
        exit();
    }

    // check expiry
    if (!preg_match('/^[0-9]{2}\/[0-9]{2}$/', $expiry)) {
        create_error_page("Data di scadenza non valida");
        exit();
    }

    // check cvv

    if (!preg_match('/^[0-9]{3}$/', $cvv)) {
        create_error_page("CVV non valido");
        exit();
    }



    // Prepare the SQL statement
    $sql = "INSERT INTO Orders (total, user_id, name, surname, email, phone, address, city, zip, card_number, card_expiry, card_cvv, note) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters
    $stmt->bind_param("disssssssssss",
                        $total,
                        $user_id,
                        $name,
                        $surname,
                        $email,
                        $phone,
                        $address,
                        $city,
                        $zip,
                        $card,
                        $expiry,
                        $cvv,
                        $note);

    if ($stmt->execute()) {
        
    } else {
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

            $sql_insert_detail = "INSERT INTO OrderDetail (quantity, order_id, product_id, color) VALUES (?, ?, ?,?)";
            $stmt = $conn->prepare($sql_insert_detail);
            $stmt->bind_param('iiis', $quantity, $order_id, $id, $item['color']);
            $stmt->execute();
            if ($stmt->error) {
                create_error_page("Errore nel database");
                exit();
            }
            $stmt->close();
            echo '///////////' . $order_id .'and' . $id . '//////////';
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
