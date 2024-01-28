<?php
    session_start();
    $lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'it';
    $i18n = include('i18n/lang.php');

    $title = $i18n['title'][$lang];
    $description = $i18n['title'][$lang];
    $keywords = "Media, Shop, 2000";
    $author = "Author";


    $header = "header.php";
    $main = "home.php";
    $footer = "footer.php";

    include "includes/utils.php";
    
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

    include "template/index.php";
?>