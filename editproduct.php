<?php 
    include "includes/utils.php";
    include "includes/db.php";

    $sql = "Select * from Category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories_html = "";
    while($row = $result->fetch_object()){
        
        $categories_html .= render("<option value='{{ category_id }}'>{{ category_name }}</option>", [
            "category_id" => $row->category_id,
            "category_name" => $row->category_name,
        ]);
    }

    echo create_page('template/index.html',[
        'header_title' =>'Modifica Prodotto | MediaShop2000',
        'header_description' => 'La pagina di modifica prodotto del sito MediaShop2000',
        'header_keywords' => "prodotto, Shop, media, games",
        'header_author' =>"MediaShop2000",

        'skip_to_main' => 'Salta al contenuto principale',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/editproduct.html'), [
            "categories" => $categories_html,
        ]),
        'page_footer' => create_page_footer(),
    ]);
?>