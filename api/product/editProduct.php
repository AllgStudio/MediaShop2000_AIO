<?php
$product_id = $_POST['product_id'] ?? "";
$product_name = $_POST['product_name'] ?? "";
$brand = $_POST['brand'] ?? "";
$price = $_POST['price'] ?? "";
$price_discount = $_POST['price_discount'] ?? "";
$description = $_POST['description'] ?? "";
$category_id = $_POST['category'] ?? "";
$color = $_POST['color'] ?? "";


if (isset($_COOKIE['user'])) {
    $user = json_decode($_COOKIE['user'], true);
    if ($user['role'] != 'admin') {
        header("Location: ../../productmanage.php");
        exit();
    }
}
// SERVER VALIDATE CHECK

if(strlen($product_name) < 3){
    header("Location: ../../500.php");
   
}
if(strlen($brand) < 3){
    header("Location: ../../500.php");
}
// check color if in right format
$colors = explode(",", $color);
if($colors[0] == " " || $colors[count($colors) - 1] == " " || trim($colors[0]) == ""){
    header("Location: ../../500.php");
}

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

if($price < $price_discount){
    header("Location: ../../500.php");
}

include "../../includes/db.php";

try {
    $conn->begin_transaction();

    // Update Product table
    $sql = "UPDATE Product SET product_name = ?, brand = ?, color = ?, price = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdsd', $product_name, $brand, $color, $price, $description, $product_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating Product table: " . $stmt->error);
    }

    // Update CategoryProduct table
    $sql = "UPDATE CategoryProduct SET category_id = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $category_id, $product_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating CategoryProduct table: " . $stmt->error);
    }

    // Update PriceCut table
    if ($price_discount !== "" && $price_discount != 0) {
        $percent = round((1 - $price_discount / $price) * 100, 0);

        // Check if there is an existing discount
        $sql = "SELECT COUNT(*) FROM PriceCut WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count > 0) {
            // Update existing discount
            $sql = "UPDATE PriceCut SET discountInPercentage = ?, new_price = ? WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('idi', $percent, $price_discount, $product_id);
        } else {
            // Insert new discount
            $sql = "INSERT INTO PriceCut (product_id, discountInPercentage, new_price) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iid', $product_id, $percent, $price_discount);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error updating/inserting into PriceCut table: " . $stmt->error);
        }
    } else {
        // Delete any existing discount if the discount is removed
        $sql = "DELETE FROM PriceCut WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting from PriceCut table: " . $stmt->error);
        }
    }

    $conn->commit();
    header("Location: ../../productmanage.php");
} catch (Exception $e) {
    $conn->rollback();
    header("Location: ../../500.php");
} finally {
    $conn->close();
    exit();
}
?>
