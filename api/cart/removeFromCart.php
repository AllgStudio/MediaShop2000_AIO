<?php
$product_id = $_POST['id'] ?? "";

if ($product_id == "") {
    header("Location: ../../cart.php");
    exit();
}
// remove from cart cookie by id
if (isset($_COOKIE['cart'])) {
    $cart = json_decode($_COOKIE['cart'], true);
    $new_cart = [];
    foreach ($cart as $item) {
        if ($item['product_id'] != $product_id) {
            $new_cart[] = $item;
        }
    }
    setcookie('cart', json_encode($new_cart), time() + (86400 * 30), "/");
}