<?php
$product_name = $_POST['product_name'] ?? "";
$brand = $_POST['brand'] ?? "";
$price = $_POST['price'] ?? "";
$price_discount = $_POST['price_discouted'] ?? "";
$description = $_POST['description'] ?? "";
$category_id = $_POST['category'] ?? "";
$color = $_POST['color'] ?? "";



if(isset($_COOKIE['user'])){
    $user = json_decode($_COOKIE['user'], true);
    if($user['role'] != 'admin'){
        header("Location: ../../productmanage.php");
        exit();
    }
}

include "../../includes/db.php";
// 
// 完整内容
                           // product_name	 brand	 color		price	description
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

header("Location: ../../productmanage.php");
exit();