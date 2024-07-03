<?php 
    include "includes/utils.php";
    echo create_page('template/index.html',[
        'header_title' =>'404 Pagina non trovata | MediaShop2000',
        'header_description' => 'La pagina di errore 404',
        'header_keywords' => "info, about, media, games",
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/404.html'), []),
        'page_footer' => create_page_footer(),
    ]);
?>