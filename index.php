<?php
    include "includes/utils.php";
    // $i18n = include('i18n/lang.php');
    $lang = get_language();

    

    $selection_1 = "";
    $selection_2 = "";
    $selection_3 = "";


    include "demo_data.php";

    foreach($card_data as $card){
        $selection_1 .= create_card($card);
    };
    foreach($card_data2 as $card){
        $selection_2 .= create_card($card);
    };
    foreach($card_data2 as $card){
        $selection_3 .= create_card($card);
    };



    echo create_page('template/index.html',[
         //'lang' => $lang,
        'header_title' =>'title',
        'header_description' => 'description',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' =>'skip_to_main',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/home.html'), [
                            'carousel' => $carousel,
                            'selection_1' => $selection_1,
                            'selection_2' => $selection_2,
                            'selection_3' => $selection_3,
                        ]),
        'page_footer' => create_page_footer(),
    ]);

?>