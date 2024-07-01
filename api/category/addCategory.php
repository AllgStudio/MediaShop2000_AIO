<?php
$category_name = $_POST['category_name'] ?? "";

if ($category_name == "") {
    header("Location: ../../categorymanage.php");
    exit();
}

if(isset($_COOKIE['user'])){
    $user = json_decode($_COOKIE['user'], true);
    if($user['role'] != 'admin'){
        header("Location: ../../categorymanage.php");
        exit();
    }
}

include "../../includes/db.php";

$sql = "INSERT INTO Category (category_name) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $category_name);
$stmt->execute();
$conn->close();

header("Location: ../../categorymanage.php");
exit();