<?php 
    session_start();
    include "includes/utils.php";
    $i18n = include('includes/i18n/lang.php');
    $lang = get_language();

    // TODO: replace javascript alert with html alert
    if (isset($_COOKIE['user'])) {
        echo "<script>alert('You are not logged in!')</script>";
        header("Location: login.php");
        exit();
    }
    if(json_decode($_COOKIE['user'], true)['role'] != 'admin'){
        echo "<script>alert('You are not admin!')</script>";
        header("Location: index.php");
        exit();
    }


    include "includes/db.php";

    echo create_page('template/index.html',[
        'lang' => $lang,
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/categorymanage.html'), [
                            "hello" => "Hello World, Category manage!",
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>