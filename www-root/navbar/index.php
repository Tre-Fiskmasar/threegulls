<header class="top-bar">
    <div class="container top-bar-container">
        <a href="<?= $path_prefix ?>/index.php" class="logo-container">
            <img id="logo" alt="<?= htmlspecialchars($siteData->Title ?? '') ?> Logo" src="<?= $path_prefix ?>../public/img/<?= htmlspecialchars($siteData->logo ?? 'logo.png') ?>">
            <h1 id="team-title"><?= htmlspecialchars($siteData->Title ?? 'Threegulls') ?></h1>
        </a>
        <nav class="main-nav">
            <ul class="nav-links">
                <li><a href="<?= $path_prefix ?>/index.php#about-team">About</a></li>
                <li><a href="<?= $path_prefix ?>/index.php#people">Team</a></li>
                <li><a href="<?= $path_prefix ?>/index.php#find-us">Find Us</a></li>
                <li><a href="<?= $path_prefix ?>/contact/index.php">Contact</a></li>
                <li><a href="<?= $path_prefix ?>/docs/index.php">Docs</a></li>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="<?= $path_prefix ?>admin/index.php" class="nav-admin-link">Admin Page</a></li>
                    <?php else: ?>
                        <li><a href="<?= $path_prefix ?>user/index.php">My Dashboard</a></li>
                    <?php endif; ?>

                    <li><a href="<?= $path_prefix ?>auth/logout.php">Logout</a></li>

                <?php else: ?>
                    
                    <li class="nav-admin-link"><a href="<?= $path_prefix ?>auth/login.php">Login</a></li>

                <?php endif; ?>

            </ul>
        </nav>
        <button class="nav-toggle" aria-label="toggle navigation">
            <span class="hamburger"></span>
        </button>
    </div>
</header>