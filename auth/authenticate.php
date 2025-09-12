<?php
session_start();

//hardcoded credentials for simplicity
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Fiskarna78$');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        header('Location: ../admin/index.php');
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}