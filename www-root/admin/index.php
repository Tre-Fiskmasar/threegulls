<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/easySQL.php';

function getJsonData($filePath) {
    if (!file_exists($filePath)) return null;
    $json = file_get_contents($filePath);
    return json_decode($json);
}

$siteData = getJsonData(__DIR__ . '/../public/data/Site.json');

$path_prefix = '../'; 

$messages = [];
try {
    $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME, db_PORT);
    $messages = $db->db_Out('contacts', '*', null, [], 'submission_date DESC');
    $db->closeConnection();
} catch (Exception $e) {
    $messages = []; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Messages</title>
    <link rel="stylesheet" href="<?= $path_prefix ?>src/styles/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include __DIR__ . '/../navbar/index.php'; ?>

    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>. <br> 
        More is comming here soon</p>
        <a href="<?= $path_prefix ?>auth/logout.php" class="logout-button">Logout</a>
    </div>

    <section class="messages-container">
        <h2>Inbox</h2>
        <?php if (empty($messages)): ?>
            <p class="no-messages">There are no new messages.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <details class="message-card">
                    <summary class="message-header">
                        <div>
                            <span class="sender-info"><?= htmlspecialchars($message['name']) ?></span>
                        </div>
                        <div class="message-date">
                            <?= date('F j, Y, g:i a', strtotime($message['submission_date'])) ?>
                        </div>
                    </summary>
                    <div class="message-content">
                        <p><strong>From:</strong> <a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></p>
                        <hr>
                        <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
</body>
</html>