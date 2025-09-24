<?php
session_start();
require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (empty($username) || empty($password)) {
        $_SESSION['signup_error'] = 'Please fill in all fields.';
        header('Location: signup.php');
        exit;
    }

    try {
        $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        $existingUser = $db->db_Out('users', 'id', 'username = ?', [$username]);
        if ($existingUser) {
            $_SESSION['signup_error'] = 'Username already exists.';
            header('Location: signup.php');
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $status = ($role === 'admin') ? 'pending' : 'approved';

        $db->db_In('users', [
            'username' => $username,
            'password_hash' => $password_hash,
            'role' => $role,
            'status' => $status
        ]);

        $_SESSION['signup_success'] = 'Registration successful. If you registered as an admin, your account requires approval.';
        header('Location: login.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['signup_error'] = 'An error occurred. Please try again.';
        header('Location: signup.php');
        exit;
    }
} else {
    header('Location: signup.php');
    exit;
}
?>