<?php
include "includes/utils.php";
include "includes/db.php";


$error_msg = "";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? 'user';

// check server side
if(password_verify($password, $password_repeat)){
    $error_msg = "Le password non corrispondono";
    create();
    exit();
}

if($role == 'admin' && $_COOKIE['user']){
    $role = json_decode($_COOKIE['user'], true)['role'] == 'admin' ? 'admin' : 'user';
}else{
    $role = 'user';
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($password == '' || $username == '') {
        $error_msg ='Campo nome utente o password è vuoto';
        create();
    } else {
        // Hash the password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO User (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $hash, $email, $role);
        $stmt->execute();
         
        // if error
        // Redirect to the home page
        //header('Location: login.php');
    }
}
create();

function create(){
    global $error_msg;

    echo create_page('template/index.html', [
        'header_title' =>'Login | MediaShop2000',
        'header_description' => 'La pagina consente di effettuare il login',
        'header_keywords' => "Login, Shop, media, games",
    
        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/register.html'), [
            "error_msg" => $error_msg,
        ]),
        'page_footer' => create_page_footer(),
    ]);
}


?>
