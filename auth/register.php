<?php
session_start();
require_once '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reg'])) {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = "This email is already registered.";
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'student')"
                );
                $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
                header("Location: login.php?success=1");
                exit();
            } catch (PDOException $e) {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — YIC Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><h1>📚 YIC Library System</h1></header>
<main>
<div class="form-wrap">
    <form method="POST" action="" id="registerForm" novalidate>
        <h2 class="page-title">📝 Create Account</h2>

        <?php if ($error): ?>
            <div class="alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div id="registerError" class="alert-error" style="display:none;"></div>

        <label for="username">Full Name</label>
        <input type="text" id="username" name="username"
               placeholder="Your full name"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               placeholder="Your email address"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Minimum 6 characters" required>

        <button type="submit" name="submit_reg">Register Now →</button>
        <p class="form-footer">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
</main>
<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>