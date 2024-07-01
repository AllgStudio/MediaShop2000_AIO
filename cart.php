<?php 
    session_start();
    include "includes/utils.php";


    include "includes/db.php";

    echo create_page('template/index.html',[

        'header_title' =>'title',
        'header_description' => 'description',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => 'skip_to_main',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/cart.html'), [
                            "hello" => "Hello World!, Cart",
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>