<?php 
    include "includes/utils.php";
    echo create_page('template/index.html',[
        'header_title' =>'500 Internal error | MediaShop2000',
        'header_description' => 'La pagina di errore 500',
        'header_keywords' => "errore, about, media, games",
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/500.html'), [
            "error" => ""
        ]),
        'page_footer' => create_page_footer(),
    ]);
?>