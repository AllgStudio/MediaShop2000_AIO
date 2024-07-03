<?php
$product_id = $_GET['product_id'] ?? "";


if ($product_id == "") {
    header("Location: ../../productmanage.php");
    exit();
}

try{
    if(isset($_COOKIE['user'])){
        $user = json_decode($_COOKIE['user'], true);
        if($user['role'] != 'admin'){
            header("Location: ../../productmanage.php");
            exit();
        }
    }

    include "../../includes/db.php";
    // 
                            // product_name	 brand	 color	 	price	description
    $sql = "DELETE FROM Product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $conn->close();
} catch(Exception $e){
    create_error_page("Errore nella rimozione del prodotto");
}

header("Location: ../../productmanage.php");
exit();