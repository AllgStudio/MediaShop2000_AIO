<?php
$product_id = $_POST['id'] ?? "";
$color = $_POST['color'] ?? "";
$quantity = $_POST['quantity'] ?? 1;

// add to cart cookie
if(isset($_COOKIE['cart'])){
    $new_array = array([
        "product_id" => $product_id,
        "color" => $color,
        "quantity" => $quantity
    ]);

    $cart = json_decode($_COOKIE['cart'], true);
    // check if product already in cart
    foreach($cart as $item){
        if($item['product_id'] == $product_id && $item['color'] == $color){
            $item['quantity'] += $quantity;
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

// go back to product detail and dont let possible go back this page
header("Location: ../../productdetail.php?id=$product_id");
exit();


