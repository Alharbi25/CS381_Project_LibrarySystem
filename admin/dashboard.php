<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$totalBooks     = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$availableBooks = $pdo->query("SELECT COUNT(*) FROM books WHERE status='available'")->fetchColumn();
$borrowedBooks  = $pdo->query("SELECT COUNT(*) FROM books WHERE status='borrowed'")->fetchColumn();
$totalStudents  = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();

$recent = $pdo->query("
    SELECT t.borrow_date, t.return_date, u.username, b.title
    FROM   transactions t
    JOIN   users u ON t.user_id = u.id
    JOIN   books b ON t.book_id = b.id
    ORDER  BY t.borrow_date DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — YIC Library Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><h1>📚 YIC Library System — Admin</h1></header>

<nav role="navigation">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="manage_books.php">📚 Manage Books</a>
    <a href="../auth/logout.php">🚪 Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
</nav>

<main class="container">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <section>
        <h2 class="page-title">📊 Dashboard Overview</h2>
        <p style="margin-bottom:16px;color:#666;">
            Welcome back, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!
        </p>

        <div class="stats-grid">
            <article class="stat-card">
                <div class="stat-number"><?= $totalBooks ?></div>
                <div class="stat-label">Total Books</div>
            </article>
            <article class="stat-card">
                <div class="stat-number" style="color:#2d6a4f;"><?= $availableBooks ?></div>
                <div class="stat-label">Available</div>
            </article>
            <article class="stat-card">
                <div class="stat-number" style="color:#b23b2c;"><?= $borrowedBooks ?></div>
                <div class="stat-label">Borrowed</div>
            </article>
            <article class="stat-card">
                <div class="stat-number"><?= $totalStudents ?></div>
                <div class="stat-label">Students</div>
            </article>
        </div>
    </section>

    <section>
        <h3 class="page-title" style="font-size:1.05rem;">🕐 Recent Transactions</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student</th><th>Book</th>
                        <th>Borrow Date</th><th>Returned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $r): ?>
                    <tr>
                        <td data-label="Student"><?= htmlspecialchars($r['username']) ?></td>
                        <td data-label="Book"><?= htmlspecialchars($r['title']) ?></td>
                        <td data-label="Borrow Date"><?= $r['borrow_date'] ?></td>
                        <td data-label="Returned">
                            <?= $r['return_date']
                                ? '<span style="color:#2d6a4f;">✅ ' . $r['return_date'] . '</span>'
                                : '<span style="color:#b23b2c;">❌ Pending</span>' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top:20px;">
            <a href="manage_books.php" class="btn"
               style="width:auto;padding:11px 28px;display:inline-block;">
               ➡️ Manage Books
            </a>
        </p>
    </section>
</main>

<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>
