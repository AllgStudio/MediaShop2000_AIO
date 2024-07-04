<?php 
    include "includes/utils.php";
    include "includes/db.php";
    $product_id = $_GET['id'] ?? 0;

    if($product_id == 0){
        header("Location: login.php");
        exit();
    }


    $sql = "Select * from Category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories_html = "";
    while($row = $result->fetch_object()){
        
        $categories_html .= render("<option value='{{ category_id }}'>{{ category_name }}</option>", [
            "category_id" => $row->category_id,
            "category_name" => $row->category_name,
        ]);
    }

    $sql = "SELECT Product.product_id as p_id, Product.product_name, Product.brand, Product.price, ProductImage.url, Category.category_name, Category.category_id, Product.color, Product.description, CategoryProduct.category_id, Product.color, PriceCut.new_price
            FROM Product 
            Left JOIN ProductImage ON Product.product_id = ProductImage.product_id
            LEFT JOIN CategoryProduct ON Product.product_id = CategoryProduct.product_id
            left JOIN Category ON CategoryProduct.category_id = Category.category_id
            LEFT JOIN PriceCut ON Product.product_id = PriceCut.product_id
    WHERE Product.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_object();


    echo create_page('template/index.html',[
        'header_title' =>'Modifica Prodotto | MediaShop2000',
        'header_description' => 'La pagina consente di modificare il singolo prodotto',
        'header_keywords' => "prodotto, Shop, media, games",
   
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/admin/editproduct.html'), [
            "categories" => $categories_html,
            "product_id" => $product->p_id,
            "product_name" => $product->product_name,
            "brand" => $product->brand,
            "color" => $product->color,
            "price" => $product->price,
            "description" => $product->description,
            "category_id" => $product->category_id,
            "price_discouted" => $product->new_price,


        ]),
        'page_footer' => create_page_footer(),
    ]);
?>