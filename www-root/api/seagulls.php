<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

session_start();

$apiKey = $_SERVER['HTTP_X_API_KEY'] ?? $_GET['api_key'] ?? null;




try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);


    if (!$apiKey && isset($_SESSION['user_id'])) {
        $keyData = $db->db_Out(
            'api_keys',
            'id, is_active, api_key',
            'user_id = ?',
            [$_SESSION['user_id']]
        );

        if ($keyData && $keyData[0]['is_active']) {
            $apiKey = $keyData[0]['api_key']; // Set API key from session
        }
    }

    if (!$apiKey) {
        http_response_code(401);
        echo json_encode(['error' => 'API key is missing.']);
        exit;
    } //moved 401 error handling from line 11

    $keyData = $db->db_Out(
        'api_keys',
        'id, is_active',
        'api_key = ?',
        [$apiKey]
    );

    if (!$keyData || !$keyData[0]['is_active']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid or inactive API key.']);
        $db->closeConnection();
        exit;
    }


    $seagulls = $db->db_Out('seagulls', 'species_name, description, habitat, image_url');

    $db->db_Set('api_keys', ['requests_count' => 'requests_count + 1'], 'id = ?', [$keyData[0]['id']]);

    $db->closeConnection();

    http_response_code(200);
    echo json_encode($seagulls);
} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['error' => 'An internal server error occurred.']);
}
?>