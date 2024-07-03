<?php
include "includes/utils.php";
include "includes/db.php";

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

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
            $query = 'SELECT * FROM User WHERE username = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            // Get the result
            $result = $stmt->get_result();

            // Check if there is a matching user
            if ($result->num_rows > 0) {
                // Fetch the user data
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, perform login actions
                    $user['password'] = null;

                    $cookie_expiry = time() + (86400 * 30); // 30 days
                    setcookie("user", json_encode($user), $cookie_expiry, "/");
                    setcookie("logged_in", true, $cookie_expiry, "/");

                    // Redirect to the home page
                    header('Location: userprofile.php');
                } else {
                    // Password is incorrect
                    create_error_page("Password errata");
                }
            } else {
                // No matching user
                create_error_page("Utente non trovato");
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        create_error_page("Errore nel database");
    }
}



echo create_page('template/index.html', [
    'header_title' => 'Login | MediaShop2000',
    'header_description' => 'La pagina di login',
    'header_keywords' => "Login, Shop, media, games",

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/login.html'), []),
    'page_footer' => create_page_footer(),
]);
