<?php 

    include "includes/utils.php";
    include "includes/db.php";

    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
    print_r($cart);

    
    $total = 0;
    $cart_html = '';

    $sql = 'SELECT * FROM Product 
            INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
            INNER JOIN PriceCut ON PriceCut.product_id = Product.product_id';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    for($i = 0; $i < count($cart); $i++) {
        $item = $cart[$i];
        $total += $item['price'];
        $cart_html .= render(file_get_contents('template/cart.item.html'), [
            'name' => $products.find($item['product_id'])['product_name'],
            'price' => $item['price'],
            'url' => $products.find($item['product_id'])['url'],
            'color' => $item['color'],
        ]);
    }

    echo create_page('template/index.html',[

        'header_title' =>'title',
        'header_description' => 'description',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => 'skip_to_main',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/cart.html'), [
                            "items" => $cart_html,
                            "total" => round($total, 2),
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>