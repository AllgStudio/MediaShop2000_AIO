<?php
include "includes/utils.php";
$id = $_GET['id'] ?? json_decode($_COOKIE['user'])->user_id ?? 0;

if ($id == 0) {
    // go to login page
    header("Location: login.php");
    exit();
}

include "includes/db.php";

try {
    $sql = "SELECT * FROM `Orders` WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_object()) {
        $orders[] = $row;
    }

    $orders_html = "";
    for ($i = 0; $i < count($orders); $i++) {
        $orders_html .= render(file_get_contents('template/user/userordermanage.item.html'), [
            "order_id" => $orders[$i]->order_id,
            "order_date" => $orders[$i]->order_datetime,
            "total_price" => $orders[$i]->total,
        ]);
    }
} catch (Exception $e) {
    create_error_page("Errore nel database");
}
echo create_page('template/index.html', [
    'header_title' => "I miei ordini | MediaShop2000",
    'header_description' => "La pagina consente di gestire i propri ordini",
    'header_keywords' => "Ordini ,Shop, media, games",
    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/user/userordermanage.html'), [
        "orders" => $orders_html,
    ]),
    'page_footer' => create_page_footer(),
]);
