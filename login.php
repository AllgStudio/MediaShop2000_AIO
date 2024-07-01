<?php
include "includes/utils.php";
include "includes/db.php";


$error_msg = "";

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// TODO: check filds server side

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($password == '' || $username == '') {
        $error_msg ='emptyfields';
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
                setcookie("user",json_encode($user), $cookie_expiry, "/"); 
                setcookie("logged_in",true, $cookie_expiry, "/");

                // Redirect to the home page
                header('Location: index.php');
            } else {
                // Password is incorrect
                $error_msg ='incorrectpassword';
            }
        } else {
            // No matching user
            $error_msg = 'usernotfound';
        }
        $stmt->close();
    }
}



echo create_page('template/index.html', [
    'header_title' =>'title',
    'header_description' => 'description',
    'header_keywords' => "Shop, media, games",
    'header_author' => "Author",

    'skip_to_main' => 'skip_to_main',

    'page_header' => create_page_header(),
    'page_main' => render(file_get_contents('template/login.html'), [
        "error_msg" => $error_msg,
        'login' => 'login',
        'username' => 'username',
        'password' => 'password',
    ]),
    'page_footer' => create_page_footer(),
]);

?>
