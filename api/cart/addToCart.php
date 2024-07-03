<?php
$product_id = $_POST['id'] ?? "";
$color = $_POST['color'] ?? "";
$quantity = $_POST['quantity'] ?? 1;

try{
    // add to cart cookie
    if(isset($_COOKIE['cart'])){
        $new_array = array([
            "product_id" => $product_id,
            "color" => $color,
            "quantity" => $quantity
        ]);

        $cart = json_decode($_COOKIE['cart'], true)??[];

        // check if product already in cart add quantity
        for($i = 0; $i < count($cart); $i++){
            if($cart[$i]['product_id']??"" == $product_id && $cart[$i]['color']??"" == $color){
                $cart[$i]['quantity'] += $quantity;
                setcookie('cart', json_encode($cart), time() + (86400 * 30), "/");
                header("Location: ../../productdetail.php?id=$product_id");
                exit();
            }
        }
        $cart = array_merge($cart, $new_array);
        setcookie('cart', json_encode($cart), time() + (86400 * 30), "/");
    }else{
        setcookie('cart', json_encode([$product_id]), time() + (86400 * 30), "/");
    }
} catch(Exception $e){
    create_error_page("Errore nell'aggiunta al carrello");
}
header("Location: ../../productdetail.php?id=$product_id");
exit();


