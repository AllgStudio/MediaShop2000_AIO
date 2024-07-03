<?php


$product_id = $_GET['id'] ?? "";

if ($product_id == "") {
    header("Location: ../../cart.php");
    exit();
}
try{
    // remove from cart cookie by id
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode($_COOKIE['cart'], true)??[];
        $new_cart = [];

        // rimovi valore invalidi
        for ($i = 0; $i < count($cart); $i++) {
            // se non esiste product_id, salta
            if ($cart[$i]['product_id']??-1 == -1) {
                continue;
            }
            // se product_id è uguale a quello che vogliamo rimuovere, salta
            if ($cart[$i]['product_id']??-1 == $product_id) {
                continue;
            }
            $new_cart[] = $cart[$i];
        }
        setcookie('cart', json_encode($new_cart), time() + (86400 * 30), "/");
    }
} catch(Exception $e){
    create_error_page("Errore nella rimozione dal carrello");
}

header("Location: ../../cart.php");
exit();

