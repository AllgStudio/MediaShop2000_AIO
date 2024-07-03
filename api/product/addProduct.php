<?php
$product_name = $_POST['product_name'] ?? "";
$brand = $_POST['brand'] ?? "";
$price = $_POST['price'] ?? 0.0;
$price_discount = $_POST['price_discouted'] ?? 0.0;
$description = $_POST['description'] ?? "";
$category_id = $_POST['category'] ?? -1;
$color = $_POST['color'] ?? "";



if(isset($_COOKIE['user'])){
    $user = json_decode($_COOKIE['user'], true);
    if($user['role'] != 'admin'){
        header("Location: ../../productmanage.php");
        exit();
    }
}

if ($product_name == "" || $brand == "" || $price == "" || $category_id == "" || $color == "") {
    header("Location: ../../500.php");
}

// SERVER VALIDATE CHECK
if (!preg_match("/^[a-zA-Z0-9 ]*$/", $product_name)) {
    header("Location: ../../500.php");
}

if (!preg_match("/^[a-zA-Z0-9 ]*$/", $brand)) {
    header("Location: ../../500.php");
}

// check color if in right format
// ex. white
// ex. white,black
if(!preg_match("/^[a-zA-Z0-9, ]*$/", $color)){
    header("Location: ../../500.php");
}

// price allow decimal
if (!preg_match("/^[0-9.]*$/", $price)) {
    header("Location: ../../500.php");
}

// price_discount allow decimal
if (!preg_match("/^[0-9.]*$/", $price_discount)) {
    header("Location: ../../500.php");
}






try{
    include "../../includes/db.php";

    $sql = "INSERT INTO Product (product_name, brand, color, price, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssds', $product_name, $brand, $color, $price, $description);
    $stmt->execute();
    $last_id = $conn->insert_id;


    $sql = "INSERT INTO CategoryProduct (category_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $category_id, $last_id );
    $stmt->execute();

    if($price_discount != "" && $price_discount != 0){
        $percent = 100 - round($price_discount / $price * 100, 0);
        $sql = "INSERT INTO PriceCut (product_id, discountInPercentage, new_price) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iid', $last_id, $percent, $price_discount);
        $stmt->execute();
    }
    $conn->close();
} catch(Exception $e){
    header("Location: ../../500.php");
}

header("Location: ../../productmanage.php");
exit();