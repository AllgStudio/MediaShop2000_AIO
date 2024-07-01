<?php 
    session_start();
    include "includes/utils.php";
    include "includes/db.php";

    $category = $_GET['category_id'] ?? "";

    $sql = "SELECT * FROM Product 
            INNER JOIN ProductImage ON Product.product_id = ProductImage.product_id
            INNER JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            INNER JOIN Category ON CategoryProduct.category_id = Category.category_id";
    
    if($category != ''){
        $sql .= " WHERE Category.category_id = ?";
    }

    $stmt = $conn->prepare($sql);
    if($category != ''){
        $stmt->bind_param("s", $category);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while($row = $result->fetch_object()){
        $products[] = $row;
    }

    // get product rate in map
    $sql = "SELECT product_id, AVG(star_rating) as rate FROM Feedback GROUP BY product_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $rate_map = [];
    while($row = $result->fetch_object()){
        $rate_map[$row->product_id] = (int)$row->rate;
    }


    $products_html = "";
    for($i = 0; $i < count($products); $i++){
        $products_html .= render(file_get_contents('template/shop.product.html'), [
            "id" => $products[$i]->product_id,
            "name" => $products[$i]->product_name,
            "brand" => $products[$i]->brand,
            "new_price" => $products[$i]->price . "€",
            "old_price" => "",
            "discount" => "0%",
            "rate" => str_repeat("⭐", $rate_map[$products[$i]->product_id]??0) ?? "",
            "rate_count" => round($rate_map[$products[$i]->product_id]??0,1)??0,
            "url" => $products[$i]->url,
            "type" => $products[$i]->category_name,
            "anno" => "",
            "discount_class" => "d-none"
        ]);
    }

    // get all category
    $sql = "SELECT * FROM Category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = [];
    while($row = $result->fetch_object()){
        $categories[] = $row;
    }

    $categories_html = "";
    $categories_html .= render(file_get_contents('template/shop.category.item.html'), [
        "category_id" => "",
        "category_name" => "Tutti",
    ]);
    for($i = 0; $i < count($categories); $i++){
        $categories_html .= render(file_get_contents('template/shop.category.item.html'), [
            "category_id" => $categories[$i]->category_id,
            "category_name" => $categories[$i]->category_name,
            "item_count" => count($products)
        ]);
    }

    

    echo create_page('template/index.html',[
        'header_title' =>'Shop | MediaShop2000',
        'header_description' => 'La pagina di shop del sito MediaShop2000',
        'header_keywords' => "Shop, media, games",
        'header_author' =>"MediaShop2000",

        'skip_to_main' => 'Salta al contenuto principale',

        'page_header' => create_page_header(),

        'page_main' => render(file_get_contents('template/shop.html'), 
                        [
                            "products" => $products_html,
                            "categories" => $categories_html
                        ]),


        'page_footer' => create_page_footer(),
    ]);
?>