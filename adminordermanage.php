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
            "order_id" => $order['order_id'],
            "user" => $order['username'],
            "date" => $order['order_datetime'],
            "total" => $order['total'] . "€",
        ]);
    }


    echo create_page('template/index.html',[
        'header_title' => "Tutti i ordini | MediaShop2000",
        'header_description' => "Il pagina del gestione di tutti gli ordini del sito",
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