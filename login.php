<?php
    session_start();
    include "includes/utils.php";
    $i18n = include('i18n/lang.php');
    $lang = get_language();


    include "includes/db.php";


    $error_msg = "";

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password']: '';

    // TODO: check filds server side

    if (isset($_POST['username']) && isset($_POST['password'])) {
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
                $_SESSION['user'] = $user;
                $_SESSION['logged_in'] = true;

                // Redirect to the home page
                header('Location: index.php');
            } else {
                // Password is incorrect
                $error_msg = $i18n['incorrectpassword'][$lang];
            }
        } else {
            // No matching user
            $error_msg = $i18n['usernotfound'][$lang];
        }
        $stmt->close();
    }



    echo create_page('template/index.html',[
        'lang' => $lang,
        'header_title' =>$i18n['title'][$lang],
        'header_description' => $i18n['description'][$lang],
        'header_keywords' => "Shop, media, games",
        'header_author' =>"Author",

        'skip_to_main' => $i18n['skip_to_main'][$lang],

        'page_header' => create_page_header(),
        'page_main' => render(file_get_contents('template/login.html'), [
                            "error_msg" => $error_msg,
                            'login' => $i18n['login'][$lang],
                            'username' => $i18n['username'][$lang],
                            'password' => $i18n['password'][$lang],
                        ]),
        'page_footer' => create_page_footer(),
    ]);
?>