<?php 
function findProductById($products, $id) {
    foreach ($products as $product) {
        if ($product['p_id'] == $id) {
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

    $sql = 'SELECT Product.product_id as p_id, Product.product_name, Product.brand, Product.price, ProductImage.url, Category.category_name, PriceCut.new_price
            FROM Product 
            Left JOIN ProductImage ON Product.product_id = ProductImage.product_id
            LEFT JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            left JOIN Category ON CategoryProduct.category_id = Category.category_id
            left JOIN PriceCut ON Product.product_id = PriceCut.product_id';
    $stmt = $conn->prepare($sql);
    try{
        $stmt->execute();
    } catch(Exception $e){
        create_error_page("Errore nel database");
    }
    $result = $stmt->get_result();
     $products = [];
    while($row = $result->fetch_assoc()){
        $products[] = $row;
    }
    $stmt->close();
    

    for($i = 0; $i < count($cart??[]); $i++) {
        $item = $cart[$i];
        $id = $item['product_id']?? null;
        if(!$id) {
            continue;
        }
        $price = findProductById($products, $id)['new_price'] ?? findProductById($products, $id)['price'] ?? 0;
        // get price from pricecut if exsist else get price from product
        $total += ($item['quantity']??1) * $price;
        $cart_html .= render(file_get_contents('template/cart.item.html'), [
            'id' => $id,
            'brand' => findProductById($products, $id)['brand'] ?? "", // 'Apple
            'name' => findProductById($products, $id)['product_name'] ?? "Prodotto senza nome",
            'price' => (findProductById($products, $id)['new_price'] ?? findProductById($products, $id)['price'] ?? 0.0) . '€',
            'url' => findProductById($products, $id)['url']??'img/logo.png',
            'color' => $item['color']??"",
            'qty' => $item['quantity']??1,
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