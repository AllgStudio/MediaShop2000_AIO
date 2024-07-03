<?php 
    include "includes/utils.php";
    include "includes/db.php";

    echo create_page('template/index.html',[
        'header_title' =>'Chi siamo | MediaShop2000',
        'header_description' => 'La pagina di chi siamo',
        'header_keywords' => "info, about, media, games",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/about.html'), []),
        'page_footer' => create_page_footer(),
    ]);


?>