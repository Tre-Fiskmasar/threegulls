<?php
session_start();
$path_prefix = '../';
include __DIR__ . '/../navbar/index.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../src/styles/styles.css">
    <link rel="stylesheet" href="signup.css">
</head>

<body>
    <div class="signup-form">
        <h1>Create Account</h1>

        <?php if (isset($_SESSION['signup_error'])): ?>
            <p class="error-message"><?= $_SESSION['signup_error']; ?></p>
            <?php unset($_SESSION['signup_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['signup_success'])): ?>
            <p class="success-message"><?= $_SESSION['signup_success']; ?></p>
            <?php unset($_SESSION['signup_success']); ?>
        <?php endif; ?>

        <form action="register.php" method="post">
            <input type="text" name="username" class="form-input" placeholder="Username" required>
            <input type="password" name="password" class="form-input" placeholder="Password" required>
            <div class="role-selection">
                <label>
                    <input type="radio" name="role" value="user" checked> User
                </label>
                <label>
                    <input type="radio" name="role" value="admin"> Admin
                </label>
            </div>
            <button type="submit" class="form-button">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>