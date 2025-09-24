<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

$action = $_POST['action'] ?? $_GET['action'] ?? null;

try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($action === 'create' && isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];
        $newApiKey = bin2hex(random_bytes(32)); 
        
        $db->db_In('api_keys', ['user_id' => $userId, 'api_key' => $newApiKey]);

    } elseif ($action === 'delete' && isset($_GET['id'])) {
        $keyId = $_GET['id'];
        $db->db_Del('api_keys', 'id = ?', [$keyId]);
    }

    $db->closeConnection();

} catch (Exception $e) {
    error_log($e->getMessage());
}

header('Location: index.php');
exit;
?>