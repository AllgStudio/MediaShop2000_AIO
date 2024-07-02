<?php 
    include "includes/utils.php";


    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
        exit();
    }

    include "includes/db.php";

    echo create_page('template/index.html',[
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/profile.html'), [
                            "hello" => "Hello World!, Profile welcome to your profile" . json_decode($_COOKIE['user'], true),
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>