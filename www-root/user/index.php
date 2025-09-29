<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../contactconfig/database.php';
require_once __DIR__ . '/../contactlib/easySQL.php';

$path_prefix = '../';
$apiKey = null;
$seagulls = [];
$keyErrorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_key_manual'])) {
    $submittedKey = trim($_POST['api_key_manual']);
    try {
        $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        $keyData = $db->db_Out('api_keys', 'id', 'api_key = ? AND user_id IS NULL', [$submittedKey]);
        
        if ($keyData) {
            $db->db_Set('api_keys', ['user_id' => $_SESSION['user_id']], 'id = ?', [$keyData[0]['id']]);
            header('Location: index.php');
            exit;
        } else {
            $keyErrorMessage = "Invalid or already claimed API key. Please try another.";
        }
        $db->closeConnection();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $keyErrorMessage = "A server error occurred. Please try again later.";
    }
}

try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    $keyData = $db->db_Out('api_keys', 'api_key', 'user_id = ?', [$_SESSION['user_id']]);
    
    if ($keyData) {
        $apiKey = $keyData[0]['api_key'];
        $seagulls = $db->db_Out('seagulls', 'species_name, description, habitat, image_url');
    }
    
    $db->closeConnection();
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="<?= $path_prefix ?>src/styles/styles.css">
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <?php include __DIR__ . '/../navbar/index.php'; ?>

    <div class="user-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        
        <div class="api-key-section">
            <h2>Your API Key</h2>
            
            <?php if ($apiKey): ?>
                <p>Use the key below to access the Seagull API.</p>
                <code class="api-key-display"><?= htmlspecialchars($apiKey) ?></code>
            <?php else: ?>
                <p>Enter an API key provided by an administrator to activate the directory.</p>
                <form action="index.php" method="post" class="key-entry-form">
                    <input type="text" name="api_key_manual" placeholder="Paste your API key here" required>
                    <button type="submit">Activate Key</button>
                </form>
                <?php if (!empty($keyErrorMessage)): ?>
                    <p class="error-message"><?= htmlspecialchars($keyErrorMessage) ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <a href="<?= $path_prefix ?>auth/logout.php" class="logout-button">Logout</a>
    </div>

    <?php if (!empty($seagulls)): ?>
        <div class="data-container">
            <h2>Seagull Species Directory</h2>
            <div class="seagull-grid">
                <?php foreach ($seagulls as $gull): ?>
                    <div class="seagull-card">
                        <img src="<?= $path_prefix . htmlspecialchars($gull['image_url']) ?>" alt="<?= htmlspecialchars($gull['species_name']) ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($gull['species_name']) ?></h3>
                            <p><strong>Habitat:</strong> <?= htmlspecialchars($gull['habitat']) ?></p>
                            <p><?= htmlspecialchars($gull['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
    <script src="./node_modules/axios/dist/axios_min.js"> </script>
    <script src="send-api.js"> </script>
</body>
</html>