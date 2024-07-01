<?php 
    session_start();
    include "includes/utils.php";
    // $i18n = include('i18n/lang.php');
    // $lang = get_language();

    include "includes/db.php";

    echo create_page('template/index.html',[
        // 'lang' => $lang,
        'header_title' =>'title',
        'header_description' => 'description',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' =>'skip_to_main',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/about.html'), [
                            "hello" => "Hello World!, About",
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>