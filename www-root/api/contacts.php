<?php
session_start();
error_log(print_r(getallheaders(), true)); //temporary debug code, can delete
header("Content-Type: application/json");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied. You must be logged in to access this resource.']);
    exit;
}

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    $contacts = $db->db_Out('contacts', 'id, name, email, message, submission_date');
    
    $db->closeConnection();

    http_response_code(200);
    echo json_encode($contacts);

} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['error' => 'An internal server error occurred.']);
}
?>