<?php


$product_id = $_GET['id'] ?? "";

if ($product_id == "") {
    header("Location: ../../cart.php");
    exit();
}
try{
    // remove from cart cookie by id
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart'], true) ?? [];
        $new_cart = [];

        // remove invalid values
        foreach ($cart as $item) {
            // if product_id is equal to the one we want to remove, skip it
            if ($item['product_id'] == $product_id) {
                continue;
            }
            $new_cart[] = $item;
        }
        setcookie('cart', json_encode($new_cart), time() + (86400 * 30), "/");
    }
} catch(Exception $e){
    create_error_page("Errore nella rimozione dal carrello");
}

header("Location: ../../cart.php");
exit();

