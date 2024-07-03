<?php
include "includes/utils.php";

if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php";

echo create_page('template/index.html', [
    'header_title' => "Checkout | MediaShop2000",
    'header_description' => "la pagina di checkout del sito MediaShop2000",
    'header_keywords' => "checkout ,Shop, media, games",
    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/checkout.html'), []),
    'page_footer' => create_page_footer(),
]);
