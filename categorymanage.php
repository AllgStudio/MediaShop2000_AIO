<?php 
    session_start();
    include "includes/utils.php";

    // // TODO: replace javascript alert with html alert
    // if (isset($_COOKIE['user'])) {
    //     echo "<script>alert('You are not logged in!')</script>";
    //     header("Location: login.php");
    //     exit();
    // }
    // if(json_decode($_COOKIE['user'], true)['role'] != 'admin'){
    //     echo "<script>alert('You are not admin!')</script>";
    //     header("Location: index.php");
    //     exit();
    // }


    include "includes/db.php";

    echo create_page('template/index.html',[
        'lang' => 'it',
        'header_title' =>'title',
        'header_description' => 'description',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => 'skip_to_main',

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/categorymanage.html'), [
                            "hello" => "Hello World, Category manage!",
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>