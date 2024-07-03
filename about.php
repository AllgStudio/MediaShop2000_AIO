<?php 
    session_start();
    include "includes/utils.php";
    // $i18n = include('i18n/lang.php');
    // $lang = get_language();

    include "includes/db.php";

    echo create_page('template/index.html',[
        // 'lang' => $lang,
        'header_title' =>'Chi siamo | MediaShop2000',
        'header_description' => 'La pagina contiene le informazioni inerenti al nostro gruppo',
        'header_keywords' => "info, about, media, games",
        'header_author' =>"MediaShop2000",

        'skip_to_main' =>'Salta al contenuto principale',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/about.html'), [
                           
                        ]),
        'page_footer' => create_page_footer(),
    ]);


?>