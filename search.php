<?php 
    session_start();
    include "includes/utils.php";
    $i18n = include('i18n/lang.php');
    $lang = get_language();

    include "includes/db.php";

    // get the search term
    $search_term = isset($_GET['word'])?$_GET['word']:'';

    echo create_page('template/index.html',[
        'lang' => $lang,
        //TODO: add specific title
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/search.html'), [
                            "hello" => "Hello World!, Search page",
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>