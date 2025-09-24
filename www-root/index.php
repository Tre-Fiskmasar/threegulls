<?php

session_start();

function getJsonData($filePath) {
    if (!file_exists($filePath)) return null;
    $json = file_get_contents($filePath);
    return json_decode($json);
}

$siteData = getJsonData(__DIR__ . '/public/data/Site.json');

$teamMembers = [];
$dataDirectory = __DIR__ . '/public/data/';
$allJsonFiles = glob($dataDirectory . '*.json');
$excludeFiles = ['Site.json', 'name.json'];

foreach ($allJsonFiles as $filePath) {
    $fileName = basename($filePath);
    if (!in_array($fileName, $excludeFiles)) {
        $personData = getJsonData($filePath);
        if ($personData) {
            $teamMembers[] = $personData;
        }
    }
}

$path_prefix = ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteData->Title ?? 'Our Team') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $path_prefix ?>src/styles/styles.css">
</head>
<body>

    <?php 
    include __DIR__ . '/navbar/index.php'; 
    ?>

    <main class="container">
        <section id="team-selling-points" style="text-align: center; padding: 2rem 0;">
             <p id="team-selling"><?= htmlspecialchars($siteData->Sellingpoint ?? '') ?></p>
        </section>

        <section id="about-team">
            <p id="team-description"><?= htmlspecialchars($siteData->Description ?? '') ?></p>
            <p id="team-finnish"><?= htmlspecialchars($siteData->Finnish ?? '') ?></p>
        </section>

        <section id="people" class="grid">
            <?php if (empty($teamMembers)): ?>
                <p style="text-align: center; color: red;">Sorry, we couldn't load the team data.</p>
            <?php else: ?>
                <?php foreach ($teamMembers as $person): ?>
                    <div class="person-card">
                        <img src="<?= $path_prefix ?>public/img/<?= htmlspecialchars($person->image) ?>" alt="Profile picture of <?= htmlspecialchars($person->Name) ?>">
                        <h2><?= htmlspecialchars($person->Name) ?></h2>
                        <p><?= htmlspecialchars($person->Description) ?></p>
                        <p><strong>Qualifications:</strong> <?= htmlspecialchars($person->qualifications) ?></p>
                        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($person->Contacts->Email) ?>"><?= htmlspecialchars($person->Contacts->Email) ?></a></p>
                        <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($person->Contacts->Number) ?>"><?= htmlspecialchars($person->Contacts->Number) ?></a></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
    
    <section id="find-us" class="find-us-section">
        <div class="container">
            <h2>Find Us</h2>
            <div class="map-container">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!m12!1m3!1d2042.6027099759233!2d17.63970697769776!3d59.205939774554814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x465f121955555555%3A0x8f6d77c0ffd6ca2b!2sNTI%20Gymnasiet%20S%C3%B6dert%C3%A4lje!5e0!3m2!1ssv!2sse!4v1756983277466!5m2!1ssv!2sse" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <a class="cta-button" href="https://ntigymnasiet.se/sodertalje/" target="_blank" rel="noopener noreferrer">Visit NTI Södertälje</a>
        </div>
    </section>

    <footer id="footer" class="contact">
        <p><?= htmlspecialchars($siteData->Footer ?? '') ?> All rights reserved<a href="Secret/try_to_find_me/index.php">.</a></p>
    </footer>

    <script src="<?= $path_prefix ?>src/nav.js"></script>
</body>
</html>