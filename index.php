<?php
    include "includes/utils.php";
    $i18n = include('includes/i18n/lang.php');
    $lang = get_language();

    
    $carousel ='
        <h1>Welocome to our website</h1>
        <p>Enjoy to find what your are need!!</p>
        <button class="btn btn-large bg-white black mt-2">' .
         $i18n['join_us'][$lang] . '</button>';

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
        'lang' => $lang,
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

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