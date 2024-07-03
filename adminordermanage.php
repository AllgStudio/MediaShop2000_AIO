<?php 
    include "includes/utils.php";


    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
        exit();
    }
    if(json_decode($_COOKIE['user'], true)['role'] != 'admin'){
        header("Location: index.php");
        exit();
    }

    include "includes/db.php";
    $sql = "SELECT * FROM Orders Inner Join User ON Orders.user_id = User.user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $orders_html = "";
    foreach($orders as $order){
        $orders_html .= render(file_get_contents('template/admin/adminordermanage.item.html'), [
            "order_id" => $order['order_id']??-1,
            "user" => $order['username']??"",
            "date" => $order['order_datetime']??"",
            "total" => ($order['total']??0.0) . "€",
        ]);
    }


    echo create_page('template/index.html',[
        'header_title' => "Tutti gli ordini | MediaShop2000",
        'header_description' => "La pagina consente di gestire tutti gli ordini effettuati sul sito",
        'header_keywords' => "Shop, media, games",
        'header_author' =>"MediaShop2000",

        'skip_to_main' => "Salta al contenuto principale",

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/adminordermanage.html'), [
                            "order_items" => $orders_html,
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>