<?php 
    include "includes/utils.php";
    include "includes/db.php";

    echo create_page('template/index.html',[
        'header_title' =>'Nuovo Categoria | MediaShop2000',
        'header_description' => 'La pagina di crea categoria del sito MediaShop2000',
        'header_keywords' => "categoria, Shop, media, games",
        'header_author' =>"MediaShop2000",

        'skip_to_main' => 'Salta al contenuto principale',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/newcategory.html'), [
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>