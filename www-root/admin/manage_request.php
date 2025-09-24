<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $userId = $_GET['id'];

    try {
        $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        if ($action === 'approve') {
            $db->db_Set('users', ['status' => 'approved'], 'id = ?', [$userId]);
        } elseif ($action === 'deny') {
            $db->db_Del('users', 'id = ?', [$userId]);
        }

        $db->closeConnection();
    } catch (Exception $e) {
        // comming later bc lazy
    }
}

header('Location: index.php');
exit;
?>