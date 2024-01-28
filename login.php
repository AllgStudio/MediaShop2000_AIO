<?php
    session_start();
    $lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'it';
    $i18n = include('i18n/lang.php');

    $title = "Login | " . $i18n['title'][$lang];
    $description = $i18n['title'][$lang];
    $keywords = "Media, Shop, 2000";
    $author = "Author";


    $header = "header.php";
    $main = "login.php";
    $footer = "footer.php";

    include "includes/utils.php";
    
    include "template/index.php";
?>