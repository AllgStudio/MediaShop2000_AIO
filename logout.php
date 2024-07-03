<?php
    setcookie('user', '', time() - 3600, '/');
    setcookie('logged_in', '', time() - 3600, '/');
    header('Location: index.php');
    exit();
?> 