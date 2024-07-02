<?php
$category_id = $_GET['category_id'] ?? "";

if ($category_id == "") {
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

$sql = "DELETE FROM Category WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$conn->close();

header("Location: ../../categorymanage.php");
exit();