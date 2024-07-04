<?php
include "includes/db.php";
include "includes/utils.php";

// get last 5 products with highest rates
try {
    $sql = "SELECT Product.product_id, Product.product_name, Product.brand, Product.price, Product.description, ProductImage.url, Category.category_name, AVG(Feedback.star_rating) as star FROM Product 
            INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
            INNER JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            INNER JOIN Category ON CategoryProduct.category_id = Category.category_id
            INNER JOIN Feedback ON Product.product_id = Feedback.product_id
            GROUP BY Product.product_id, Product.product_name, Product.brand, Product.price, Product.description, ProductImage.url, Category.category_name
            ORDER BY Product.product_id DESC LIMIT 5";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_object()) {
        $products[] = $row;
    }

    $discout_map_percent = [];
    $discout_map_price = [];
    $sql = "SELECT * FROM PriceCut";
    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();
    while ($row = $result->fetch_object()) {
        $discout_map_percent[$row->product_id] = $row->discountInPercentage;
        $discout_map_price[$row->product_id] = $row->new_price;
    }

    //Product.product_id, Product.product_name, Product.brand, Product.price, Product.description, ProductImage.url, Category.category_name, AVG(Feedback.star_rating) as star FROM Product 

    $products_html = "";
    for ($i = 0; $i < count($products); $i++) {
        $products_html .= render(file_get_contents('template/shop.product.html'), [
            "id" => $products[$i]->product_id,
            "name" => $products[$i]->product_name,
            "brand" => $products[$i]->brand,
            "new_price" => $discout_map_price[$products[$i]->product_id] ?? false ? $discout_map_price[$products[$i]->product_id] : $products[$i]->price . "€",
            "old_price" => $discout_map_price[$products[$i]->product_id] ?? false ? $products[$i]->price . "€" : "",
            "discount" => $discout_map_percent[$products[$i]->product_id] ?? false ? "-" . $discout_map_percent[$products[$i]->product_id] . "%" : "0%",
            "rate" => str_repeat("⭐", $products[$i]->star) ?? "",
            "rate_count" => round($products[$i]->star ?? 0, 1) ?? 0,
            "url" => $products[$i]->url,
            "type" => $products[$i]->category_name,
            "anno" => "",
            "discount_class" => $discout_map_percent[$products[$i]->product_id] ?? false ? "" : "d-none",
        ]);
    }
} catch (Exception $e) {
    create_error_page("Errore nel database");
}


echo create_page('template/index.html', [
    'header_title' => 'MediaShop 2000',
    'header_description' => 'MediaShop 2000, il negozio online per i tuoi acquisti multimediali!',
    'header_keywords' => "Shop, media, games",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/home.html'), [
        'selection_2' => $products_html,
    ]),
    'page_footer' => create_page_footer(),
]);
