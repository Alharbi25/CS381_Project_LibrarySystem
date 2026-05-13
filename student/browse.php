<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title ASC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY title ASC");
}
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books — YIC Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header><h1>📚 YIC Library System</h1></header>

<nav role="navigation">
    <a href="browse.php">📚 Browse</a>
    <a href="history.php">📋 My History</a>
    <a href="../auth/logout.php">🚪 Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
</nav>

<main class="container">
    <section>
        <h2 class="page-title">📚 Browse Books</h2>
        <p style="margin-bottom:16px; color:#666;">
            Hello, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Browse and borrow below.
        </p>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <form method="GET" action="" class="search-form" role="search">
            <input type="text" name="search"
                   placeholder="Search by title or author…"
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" style="width:auto;padding:11px 20px;">🔍 Search</button>
            <?php if ($search): ?>
                <a href="browse.php" class="btn-clear">✕ Clear</a>
            <?php endif; ?>
        </form>

        <?php if (empty($books)): ?>
            <div class="alert-warning">No books found for "<?= htmlspecialchars($search) ?>".</div>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Title</th><th>Author</th>
                        <th>Category</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $b): ?>
                    <tr>
                        <td data-label="Title"><?= htmlspecialchars($b['title']) ?></td>
                        <td data-label="Author"><?= htmlspecialchars($b['author']) ?></td>
                        <td data-label="Category"><?= htmlspecialchars($b['category']) ?></td>
                        <td data-label="Status">
                            <span class="status-badge status-<?= $b['status'] ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </td>
                        <td data-label="Action">
                            <?php if ($b['status'] === 'available'): ?>
                                <a href="../actions/borrow.php?book_id=<?= $b['id'] ?>"
                                   class="btn-borrow"
                                   onclick="return confirm('Borrow: <?= htmlspecialchars($b['title'], ENT_QUOTES) ?>?');">
                                   📖 Borrow
                                </a>
                            <?php else: ?>
                                <span style="color:#999;font-size:13px;">Not Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>
</main>

<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>
