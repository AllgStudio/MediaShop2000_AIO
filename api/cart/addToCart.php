<?php
$product_id = $_POST['id'] ?? "";
$color = $_POST['color'] ?? "";
$quantity = $_POST['quantity'] ?? 1;

try{
    // add to cart cookie
    $new_array = array([
        "product_id" => $product_id,
        "color" => $color,
        "quantity" => $quantity
    ]);
    print_r($new_array);

    if(isset($_COOKIE['cart'])){
        $cart = json_decode($_COOKIE['cart'], true)??[];

        // check if product already in cart add quantity
        for($i = 0; $i < count($cart); $i++){

            $current_cart_product_id = $cart[$i]['product_id']??-1;
            $current_cart_color = $cart[$i]['color']??"";
            $current_cart_quantity = $cart[$i]['quantity']??0;

            if(
                $current_cart_product_id == $product_id &&
                $current_cart_color == $color
            ){
                $cart[$i]['quantity'] += $quantity;
                setcookie('cart', json_encode($cart), time() + (86400 * 30), "/");
                header("Location: ../../productdetail.php?id=$product_id");
                exit();
            }
        }

        $cart = array_merge($cart, $new_array);
        setcookie('cart', json_encode($cart), time() + (86400 * 30), "/");
    }else{
        setcookie('cart', json_encode($new_array), time() + (86400 * 30), "/");
    }
} catch(Exception $e){
    header("Location: ../../500.php");
}
header("Location: ../../productdetail.php?id=$product_id");
exit();


