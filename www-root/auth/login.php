<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../src/styles/styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-form">
        <h1>Admin Login</h1>
        
        <?php if (isset($_SESSION['login_error'])): ?>
            <p class="error-message"><?= $_SESSION['login_error']; ?></p>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form action="authenticate.php" method="post">
            <input type="text" name="username" class="form-input" placeholder="Username" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <button type="submit" class="form-button">Login</button>
        </form>
    </div>
</body>
</html>