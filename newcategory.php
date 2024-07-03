<?php 
    include "includes/utils.php";
    include "includes/db.php";

    echo create_page('template/index.html',[
        'header_title' =>'Nuovo Categoria | MediaShop2000',
        'header_description' => 'La pagina consente di creare nuove categorie',
        'header_keywords' => "categoria, Shop, media, games",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/newcategory.html'), [
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>