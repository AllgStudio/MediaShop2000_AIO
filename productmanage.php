<?php
session_start();
include "includes/utils.php";

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
}
if (json_decode($_COOKIE['user'], true)['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include "includes/db.php";

try {

    $sql = "SELECT * FROM Product INNER JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
    INNER JOIN Category ON CategoryProduct.category_id = Category.category_id
    Order by Product.product_id";
    $result = $conn->query($sql);
    $product_items = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_items .= render(file_get_contents('template/admin/productmanage.item.html'), [
                "product_id" => $row['product_id'],
                "product_name" => $row['product_name'],
                "brand" => $row['brand'],
                "color" => $row['color'],
                "price" => $row['price'] . "€",
                "description" => $row['description'],
                "category_name" => $row['category_name'],
            ]);
        }
    }
} catch (Exception $e) {
    create_error_page("Errore nel database");
}

echo create_page('template/index.html', [
    'header_title' => "Gestione Prodotti | MediaShop2000",
    'header_description' => "La pagina di gestione dei prodotti del sito MediaShop2000",
    'header_keywords' => "gestione, prodotto, media, games",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/admin/productmanage.html'), [
        "list" => $product_items
    ]),
    'page_footer' => create_page_footer(),
]);
