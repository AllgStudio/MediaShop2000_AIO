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

print_r($_FILES);

$image_path = "";
if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
    $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
    $filename = $_FILES["product_image"]["name"];
    $filetype = $_FILES["product_image"]["type"];
    $filesize = $_FILES["product_image"]["size"];

    // Verify file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists($ext, $allowed)) {
        echo "图片格式不对";
        // header("Location: ../../500.php");
        // exit();
    }

    // Verify file size - 5MB maximum
    $maxsize = 5 * 1024 * 1024;
    if ($filesize > $maxsize) {
        echo "图片太大";
        // header("Location: ../../500.php");
        // exit();
    }

    // Verify MIME type
    if (in_array($filetype, $allowed)) {
        
        $filename = uniqid() . "_" . $filename;
        $image_path = "upload/" . $filename;
        $real_path = "../../" . $image_path;
        if (!is_dir("../../upload")) {
            mkdir("../../upload");
        }
        // Check whether file exists before uploading it
        if (file_exists($real_path)) {
            echo "文件已经存在";
            // header("Location: ../../500.php");
            // exit();
        } else {
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $real_path);
            echo "文件上传成功";
            echo $real_path;
        }
    } else {
        // header("Location: ../../500.php");
        echo "图片格式不对";
        exit();
    }
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

    $sql = "INSERT INTO ProductImage (product_id, url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $last_id, $image_path);
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