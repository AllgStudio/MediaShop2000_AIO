<?php
include "includes/utils.php";
include "includes/db.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? 'user';

// check server side
if (password_verify($password, $password_repeat)) {
    create_error_page("Le password non corrispondono");
    exit();
}

if ($role == 'admin' && $_COOKIE['user']) {
    $role = json_decode($_COOKIE['user'], true)['role'] == 'admin' ? 'admin' : 'user';
} else {
    $role = 'user';
}

if (isset($_POST['username']) && isset($_POST['password'])) {

    try {
        if (($username) < 3 || ($password) < 3) {
            create_error_page("Password o username troppo corti");
        }

        if ($password == '' || $username == '') {
            create_error_page("Campo nome utente o password è vuoto");
        } else {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query
            $sql = "INSERT INTO User (usernam, password, email, role) VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $username, $hash, $email, $role);
            $stmt->execute();
            // if error go to 500 page
            if ($stmt->error) {
                throw new Exception($stmt->error);
            }

            header('Location: login.php');
        }
    } catch (Exception $e) {
        create_error_page("La registrazione non è andata a buon fine");
    }
}


echo create_page('template/index.html', [
    'header_title' => 'Login | MediaShop2000',
    'header_description' => 'La pagina di login',
    'header_keywords' => "Login, Shop, media, games",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/register.html'), []),
    'page_footer' => create_page_footer(),
]);
