<?php
session_start();
include "includes/utils.php";
$product_id = $_GET['id'] ?? "";
include "includes/db.php";

if ($product_id == "") {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM Product 
            INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
            INNER JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            INNER JOIN Category ON CategoryProduct.category_id = Category.category_id
            WHERE Product.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_object();

$colors_html = "";
$colors = str_contains($product->color, ",") ? explode(",", $product->color) : [$product->color];
print_r($colors);
foreach ($colors as $color) {
    $colors_html .= render(file_get_contents('template/productdetail.radio.html'), [
        "id" => $product->product_id,
        "name" => $color,
        "key" => "color",
    ]);
}

$size_html = "";

$size_html .= render(file_get_contents('template/productdetail.radio.html'), [
    "id" => $product->product_id,
    "name" => "Normale",
    "key" => "size",
]);

$comments_html = "";
$avg_media_rate = 0;
$tot_rate = 0;

$sql = "SELECT * FROM Feedback INNER JOIN User ON Feedback.user_id = User.user_id WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$result = $stmt->get_result();

for($result->data_seek(0); $row = $result->fetch_object();){
    $avg_media_rate += $row->star_rating;
    $tot_rate++;
    $comments_html .= render(file_get_contents('template/productdetail.comment.html'), [
        "name" => $row->username,
        "comment" => $row->description,
        "rate_star" => str_repeat("⭐", $row->star_rating),
        "rate" => $row->star_rating,
    ]);
}


echo create_page('template/index.html', [
    'lang' => "it",
    'header_title' => $product->product_name . " - Media Shop 2000",
    'header_description' => $product->description,
    'header_keywords' => $product->product_name . ", media, games",
    'header_author' => "Meida Shop 2000",

    'skip_to_main' => "Salta al contenuto principale",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/productdetail.html'), [
        "id" => $product->product_id,
        "url" => $product->url,
        "name" => $product->product_name,
        "brand" => $product->brand,
        "new_price" => "€".$product->price,
        "old_price" => "",
        "specific" => $product->category_name,
        "description" => $product->description,
        "colors" => $colors_html,
        "sizes" => $size_html,
        "rate_star" => str_repeat("⭐", round($avg_media_rate / $tot_rate, 1) ?? 1) ?? "",
        "rate" => round($avg_media_rate / $tot_rate, 1) ?? 1,
        "comments" => $comments_html,
    ]),
    'page_footer' => create_page_footer(),
]);
