<?php 
    include "includes/utils.php";
    echo create_page('template/index.html',[
        'header_title' =>'Pagamento con successo | MediaShop2000',
        'header_description' => 'La pagina di pagamento effettuato con successo',
        'header_keywords' => "pagamento, user, media, games",
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/productpaid.html'), [
            "error" => "",
        ]),
        'page_footer' => create_page_footer(),
    ]);
?>