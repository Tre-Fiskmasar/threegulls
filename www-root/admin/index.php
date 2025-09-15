<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

function getJsonData($filePath) {
    if (!file_exists($filePath)) return null;
    $json = file_get_contents($filePath);
    return json_decode($json);
}

$siteData = getJsonData(__DIR__ . '/../public/data/Site.json');

$path_prefix = '../'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../src/styles/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
    include __DIR__ . '/../navbar/index.php'; 
    ?>
    <div class="admin-container">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>You have successfully logged in.</p>
        <p>From here, you will be able to edit the website content.</p>
        
        <a href="<?= $path_prefix ?>auth/logout.php" class="logout-button">Logout</a>
    </div>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
</body>
</html>