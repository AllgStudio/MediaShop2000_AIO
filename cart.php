<?php 
function findProductById($products, $id) {
    foreach ($products as $product) {
        if ($product['product_id'] == $id) {
            return $product;
        }
    }
    return null; // 如果未找到，返回null
}

    include "includes/utils.php";
    include "includes/db.php";

    // [{"product_id":"1","color":"white","quantity":"1"}]
    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
    $total = 0;
    $cart_html = '';

    $sql = 'SELECT * FROM Product 
            INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
            INNER JOIN PriceCut ON PriceCut.product_id = Product.product_id';
    $stmt = $conn->prepare($sql);
    try{
        $stmt->execute();
    } catch(Exception $e){
        create_error_page("Errore nel database");
    }
    $result = $stmt->get_result();
    // Array ( [0] => Array ( [product_id] => 1 [product_name] => iPhone 12 [brand] => Apple [color] => black,white [price] => 299.99 [description] => A high-end smartphone [image_id] => 1 [url] => https://picsum.photos/200/200?a=ggj0 [discountInPercentage] => 10 [new_price] => 269.99 ) [1] => Array ( [product_id] => 2 [product_name] => MacBook Pro [brand] => Apple [color] => silver [price] => 999.99 [description] => A powerful laptop [image_id] => 2 [url] => https://picsum.photos/200/200?a=fgj0 [discountInPercentage] => 20 [new_price] => 799.99 ) )
    $products = [];
    while($row = $result->fetch_assoc()){
        $products[] = $row;
    }
    $stmt->close();
    

    for($i = 0; $i < count($cart); $i++) {
        $item = $cart[$i];
        $id = $item['product_id']?? null;
        if(!$id) {
            continue;
        }
        $price = findProductById($products, $id)['new_price'] ?? findProductById($products, $id)['price'];
        // get price from pricecut if exsist else get price from product
        $total += $price;
        $cart_html .= render(file_get_contents('template/cart.item.html'), [
            'id' => $id,
            'brand' => findProductById($products, $id)['brand'], // 'Apple
            'name' => findProductById($products, $id)['product_name'],
            'price' => $price,
            'url' => findProductById($products, $id)['url'],
            'color' => $item['color'],
            'qty' => $item['quantity'],
        ]);
    }

    echo create_page('template/index.html',[

        'header_title' =>'Carrello | MediaShop2000',
        'header_description' => 'Il tuo carrello su MediaShop2000',
        'header_keywords' => "carrello, Shop, media, games",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/cart.html'), [
                            "items" => $cart_html,
                            "total" => round($total, 2),
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>