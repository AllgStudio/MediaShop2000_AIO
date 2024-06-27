<?php 
    session_start();
    include "includes/utils.php";
    $i18n = include('includes/i18n/lang.php');
    $lang = get_language();

    $product_id = isset($_GET['product_id'])?$_GET['product_id']:'';

    include "includes/db.php";

    echo create_page('template/index.html',[
        'lang' => $lang,
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/productdetail.html'), [
                            "hello" => $product_id?$product_id:"Hello World, Product _GET is empty!"
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>