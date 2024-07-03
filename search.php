<?php
session_start();
include "includes/utils.php";
include "includes/db.php";

// get the search term
$search_term = $_GET['search'] ?? '';
$search_term_sql = "%" . $search_term . "%";
try {
    $sql = "SELECT Product.product_id as p_id, Product.product_name, Product.brand, Product.price, ProductImage.url, Category.category_name
            FROM Product 
            Left JOIN ProductImage ON Product.product_id = ProductImage.product_id
            LEFT JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            left JOIN Category ON CategoryProduct.category_id = Category.category_id
            WHERE Product.product_name LIKE ?
            OR Product.description LIKE ?
            OR Product.brand LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_term_sql, $search_term_sql, $search_term_sql);

    $stmt->execute();

    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_object()) {
        $products[] = $row;
    }
    $stmt->close();

    if ($products == []) {
        echo create_page('template/index.html', [
            'header_title' => "Nessun risultato per " . $search_term . " | MediaShop2000",
            'header_description' => "Nessun risultato per " . $search_term . " su MediaShop2000",
            'header_keywords' => "ricerca, Shop, media, games",
            'page_header' => create_page_header(),
            'page_main' => render(file_get_contents('template/search.html'), [
                "search_results" => "<p class='text-paragraph' role='alert' aria-live='polite'>Nessun risultato per " . $search_term . "</p>",
            ]),
            'page_footer' => create_page_footer(),
        ]);
        exit();
    }



    // get product rate in map
    $sql = "SELECT product_id, AVG(star_rating) as rate FROM Feedback GROUP BY product_id";
    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();
    $rate_map = [];
    while ($row = $result->fetch_object()) {
        $rate_map[$row->product_id] = (int)$row->rate;
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

    $products_html = "";
    for ($i = 0; $i < count($products); $i++) {
        $products_html .= render(file_get_contents('template/shop.product.html'), [
            "id" => $products[$i]->p_id,
            "name" => $products[$i]->product_name,
            "brand" => $products[$i]->brand,
            "new_price" => $discout_map_price[$products[$i]->p_id] ?? false ? $discout_map_price[$products[$i]->p_id] : $products[$i]->price . "€",
            "old_price" => $discout_map_price[$products[$i]->p_id] ?? false ? $products[$i]->price . "€" : "",
            "discount" => $discout_map_percent[$products[$i]->p_id] ?? false ? "-" . $discout_map_percent[$products[$i]->p_id] . "%" : "0%",
            "rate" => str_repeat("⭐", $rate_map[$products[$i]->p_id] ?? 5) ?? "",
            "rate_count" => round($rate_map[$products[$i]->p_id] ?? 0, 1) ?? 0,
            "url" => $products[$i]->url ?? "img/logo.png",
            "type" => $products[$i]->category_name ?? "Generale",
            "anno" => "",
            "discount_class" => $discout_map_percent[$products[$i]->p_id] ?? false ? "" : "d-none",
        ]);
    }
} catch (Exception $e) {
    create_error_page("Errore nel database");
}

echo create_page('template/index.html', [
    'header_title' => $search_term . " - Cerca | MediaShop2000",
    'header_description' => "Cerca i tuoi prodotti preferiti su MediaShop2000",
    'header_keywords' => "ricerca, Shop, media, games",
    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/search.html'), [
        "search_results" => $products_html,
    ]),
    'page_footer' => create_page_footer(),
]);
