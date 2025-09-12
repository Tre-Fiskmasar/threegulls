<header class="top-bar">
    <div class="container top-bar-container">
        <a href="<?= $path_prefix ?>index.php" class="logo-container">
            <img id="logo" alt="<?= htmlspecialchars($siteData->Title ?? '') ?> Logo" src="<?= $path_prefix ?>public/img/<?= htmlspecialchars($siteData->logo ?? 'logo.png') ?>">
            <h1 id="team-title"><?= htmlspecialchars($siteData->Title ?? 'Team Title') ?></h1>
        </a>
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="<?= $path_prefix ?>index.php#about-team">About</a></li>
                <li><a href="<?= $path_prefix ?>index.php#people">Team</a></li>
                <li><a href="<?= $path_prefix ?>index.php#find-us">Find Us</a></li>
                <li><a href="<?= $path_prefix ?>contact/index.php">Contact</a></li>
                <li class="nav-admin-link"><a href="<?= $path_prefix ?>admin/index.php">Admin page</a></li>
            </ul>
        </nav>
        <button class="nav-toggle" aria-label="toggle navigation">
            <span class="hamburger"></span>
        </button>
    </div>
</header>