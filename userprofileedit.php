<?php
include "includes/utils.php";
include "includes/db.php";

$id = $_GET['id'] ?? json_decode($_COOKIE['user'])->user_id ?? 0;

if ($id == 0) {
    // go to login page
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['new_email'] ?? '';
    $new_user = $_POST['new_username'] ?? '';
    $password = $_POST['new_password'] ?? '';
    try {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            create_error_page($email);
            exit();
        }
        $sql = "UPDATE User SET email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $id);

        $stmt->execute();
        if ($stmt->error) {
            create_error_page("Errore nella aggiorna dell'email");
            exit();
        }
    } catch (Exception $e) {
        create_error_page("Errore nella database");
    }
    // Redirect to logout.php
    header("Location: userprofile.php");
    exit();
}

$sql = "SELECT * FROM User WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_object();
if (!$user) {
    header("Location: 404.php");
    exit();
}


echo create_page('template/index.html', [
    'header_title' => ($user->username . " - Profilo | MediaShop"),
    'header_description' => "La pagina consente di modificare il profilo dell'utente " . $user->username . " su MediaShop.",
    'header_keywords' => "Profile ,Shop, media, games",
    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/user/userprofileedit.html'), [
        "username" => $user->username,
        "created_at" => $user->creation_datetime,
        "email" => $user->email,
        "user_id" => $user->user_id,
        "user_type_name" => $user->role == "admin" ? "Amministratore" : "Utente",
    ]),
    'page_footer' => create_page_footer(),
]);
