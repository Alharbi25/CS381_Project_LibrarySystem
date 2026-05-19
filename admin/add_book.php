<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if (empty($title) || empty($author) || empty($category)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (title, author, category, status) VALUES (?, ?, ?, 'available')");
            $stmt->execute([$title, $author, $category]);
            $success = "Book added successfully!";
        } catch (PDOException $e) {
            $error = "Failed to add book. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book — YIC Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><h1>📚 YIC Library System — Admin</h1></header>
<nav role="navigation">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="manage_books.php">📚 Manage Books</a>
    <a href="add_book.php">➕ Add Book</a>
    <a href="../auth/logout.php">🚪 Logout</a>
</nav>
<main class="container">
    <section>
        <h2 class="page-title">➕ Add New Book</h2>

        <?php if ($success): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" style="max-width:500px;">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title"
                   placeholder="Enter book title"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>

            <label for="author">Author</label>
            <input type="text" id="author" name="author"
                   placeholder="Enter author name"
                   value="<?= htmlspecialchars($_POST['author'] ?? '') ?>" required>

            <label for="category">Category</label>
            <input type="text" id="category" name="category"
                   placeholder="e.g. Web, AI, Security"
                   value="<?= htmlspecialchars($_POST['category'] ?? '') ?>" required>

            <button type="submit" name="add_book" style="margin-top:10px;">
                ➕ Add Book
            </button>
        </form>

        <a href="manage_books.php" class="back-link">← Back to Manage Books</a>
    </section>
</main>
<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>