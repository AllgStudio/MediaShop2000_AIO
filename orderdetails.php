<?php 
    include "includes/utils.php";
    include "includes/db.php";

    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
        exit();
    }

    $order_id = $_GET['order_id'] ?? "";
    if ($order_id == "") {
        header("Location: index.php");
        exit();
    }

    $sql = "SELECT * FROM OrderDetail 
            INNER JOIN Product ON OrderDetail.product_id = Product.product_id
            WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderdetails = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $orderdetails_html = "";
    $total = 0;
    foreach($orderdetails as $orderdetail){
        $total += $orderdetail['price'] * $orderdetail['quantity'];
        $orderdetails_html .= render(file_get_contents('template/user/orderdetails.item.html'), [
            "product_name" => $orderdetail['product_name'],
            "brand" => $orderdetail['brand'],
            "color" => $orderdetail['color'],
            "price" => $orderdetail['price'],
            "description" => $orderdetail['description'],
            "quantity" => $orderdetail['quantity'],
            "total" => $orderdetail['price'] * $orderdetail['quantity'] . "€",
        ]);
    }

    echo create_page('template/index.html',[
        'header_title' =>'Order Details | MediaShop2000',
        'header_description' => "Il pagina del dettagli dell'ordine del sito",
        'header_keywords' => "ordine, media, games",
        'header_author' => "MediaShop2000",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/user/orderdetails.html'), [
                            "items" => $orderdetails_html,
                            "order_id" => $order_id,
                            "total"=> $total . "€",
                            "callback" => $_GET['callback'] ?? "userprofile.php",
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>