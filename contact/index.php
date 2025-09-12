<?php
session_start(); 

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/lib/easySQL.php';


$path_prefix = ''; 
$message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_message = trim($_POST['message'] ?? '');
    $isValid = true;

    if (empty($name) || empty($email) || empty($user_message)) {
        $message = '<div class="status-message error">Please fill in all fields.</div>';
        $isValid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="status-message error">Please enter a valid email address.</div>';
        $isValid = false;
    }

    if ($isValid) {
        try {
            $db = new EasySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            
            $dataToInsert = [
                'name' => $name,
                'email' => $email,
                'message' => $user_message
            ];
            
            $db->db_Ins('contacts', $dataToInsert);
            $db->closeConnection();

            $message = '<div class="status-message success">Thank you! Your message has been sent successfully.</div>';

        } catch (Exception $e) {
            $message = '<div class="status-message error">An error occurred. Please try again later.</div>';
        }
    }
}

$siteData = getJsonData(__DIR__ . '/public/data/Site.json');

function getJsonData($filePath) {
    if (!file_exists($filePath)) return null;
    $json = file_get_contents($filePath);
    return json_decode($json);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?= htmlspecialchars($siteData->Title ?? '') ?></title>
    <link rel="stylesheet" href="<?= $path_prefix ?>src/styles/style.css">
    <link rel="stylesheet" href="src/styles/contactStyle.css">
</head>
<body>
    <?php 
        include __DIR__ . '/navbar/index.php'; 
    ?>

    <main class="contact-main">
        <div class="wrapper">
            <h2>Contact Us</h2>
            <p class="form-intro">Have a question? We'd love to hear from you.</p>
            
            <?= $message ?>

            <form id="form" action="contact.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Your Full Name" maxlength="100" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" maxlength="100" required>

                <label for="message">Message:</label>
                <textarea placeholder="Write your message here..." name="message" id="message" rows="6" required></textarea>

                <button id="send-btn" type="submit">Send Message</button>
            </form>
        </div>
    </main>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
</body>
</html>