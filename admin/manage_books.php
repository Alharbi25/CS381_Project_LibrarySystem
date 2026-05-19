<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$books = $pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books — YIC Library Admin</title>
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
        <h2 class="page-title">📚 Manage Books</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">❌ <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <a href="add_book.php" class="btn"
           style="width:auto;padding:10px 20px;display:inline-block;margin-bottom:16px;">
            ➕ Add New Book
        </a>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Title</th><th>Author</th>
                        <th>Category</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $b): ?>
                    <tr>
                        <td data-label="ID"><?= $b['id'] ?></td>
                        <td data-label="Title"><?= htmlspecialchars($b['title']) ?></td>
                        <td data-label="Author"><?= htmlspecialchars($b['author']) ?></td>
                        <td data-label="Category"><?= htmlspecialchars($b['category']) ?></td>
                        <td data-label="Status">
                            <span class="status-badge status-<?= $b['status'] ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </td>
                        <td data-label="Action">
                            <a href="delete_book.php?id=<?= $b['id'] ?>"
                               class="btn-delete"
                               onclick="return confirm('Delete: <?= htmlspecialchars($b['title'], ENT_QUOTES) ?>?\nThis cannot be undone.');">
                               🗑️ Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </section>
</main>
<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>
