<?php
include "includes/utils.php";
include "includes/db.php";

try {
    $sql = "Select * from Category";
    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $result = $stmt->get_result();
    $categories_html = "";
    while ($row = $result->fetch_object()) {
        $categories_html .= render("<option value='{{ category_id }}'>{{ category_name }}</option>", [
            "category_id" => $row->category_id,
            "category_name" => $row->category_name,
        ]);
    }
} catch (Exception $e) {
    create_error_page("Errore nel database");
}

echo create_page('template/index.html', [
    'header_title' => 'Nuovo Prodotto | MediaShop2000',
    'header_description' => 'La pagina di crea prodotto del sito MediaShop2000',
    'header_keywords' => "nuovo, prodotto, Shop, media, games",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/admin/newproduct.html'), [
        "categories" => $categories_html,
    ]),
    'page_footer' => create_page_footer(),
]);
