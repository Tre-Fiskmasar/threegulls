<?php

session_start();

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Please enter username and password.';
        header('Location: login.php');
        exit;
    }

    if (defined('SUPER_ADMIN_USERNAME') && $username === SUPER_ADMIN_USERNAME && $password === SUPER_ADMIN_PASSWORD) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'Super Admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['user_id'] = 0; 
        header('Location: ../admin/index.php');
        exit;
    }

    try {
        $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        $user = $db->db_Out('users', '*', 'username = ?', [$username]);
        $db->closeConnection();

        if ($user && password_verify($password, $user[0]['password_hash'])) {
            if ($user[0]['status'] === 'pending') {
                $_SESSION['login_error'] = 'Your account is pending approval.';
                header('Location: login.php');
                exit;
            }

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user[0]['username'];
            $_SESSION['role'] = $user[0]['role'];
            $_SESSION['user_id'] = $user[0]['id'];

            if ($_SESSION['role'] === 'admin') {
                header('Location: ../admin/index.php');
            } else {
                header('Location: ../user/index.php');
            }
            exit;

        } else {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: login.php');
            exit;
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
        $_SESSION['login_error'] = 'An error occurred. Please try again.';
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>