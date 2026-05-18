<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/csrf_helper.php'; 

if (isset($_SESSION['user_id'])) {
    header($_SESSION['role'] === 'admin' ? "Location: ../admin/dashboard.php" : "Location: ../student/browse.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

  
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request. Please try again.";
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                header($user['role'] === 'admin' ? "Location: ../admin/dashboard.php" : "Location: ../student/browse.php");
                exit();
            } else {
                $error = "Invalid email or password.";
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
    <title>Login — YIC Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><h1>📚 YIC Library System</h1></header>
<main>
<div class="form-wrap">
    <form method="POST" action="" id="loginForm" novalidate>
        <?= csrfInput() ?> <!-- ✅ PHASE 4: CSRF token -->
        <h2 class="page-title">🔐 Welcome Back</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
            <div class="alert-success">✅ Registration successful! You can now log in.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div id="loginError" class="alert-error" style="display:none;"></div>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               placeholder="e.g. admin@yic.edu.sa"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Enter your password" required>

        <button type="submit" name="login">Login →</button>
        <p class="form-footer">New student? <a href="register.php">Register here</a></p>
    </form>
</div>
</main>
<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>