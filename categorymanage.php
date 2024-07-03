<?php 
    session_start();
    include "includes/utils.php";

    if (!isset($_COOKIE['user'])) {
        header("Location: login.php");
        exit();
    }
    if(json_decode($_COOKIE['user'], true)['role'] != 'admin'){
        header("Location: login.php");
        exit();
    }


    include "includes/db.php";

    $sql = "SELECT * FROM Category";
    $result = $conn->query($sql);
    $category_items = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $category_items .= render(file_get_contents('template/admin/categorymanage.item.html'), [
                "category_id" => $row['category_id'],
                "category_name" => $row['category_name'],
            ]);
        }
    }

    echo create_page('template/index.html',[
        'header_title' =>'Gestione categoria',
        'header_description' => 'La pagina consente di gestire le categorie presenti sul sito',
        'header_keywords' => "Shop, category, media, games",
        'header_author' =>"MediaShop2000",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/categorymanage.html'), [
                            "category_items" => $category_items,
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>