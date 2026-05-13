<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT t.id, b.title, b.author, b.id AS book_id,
           t.borrow_date, t.return_date, t.fine_amount, b.status AS book_status
    FROM   transactions t
    JOIN   books b ON t.book_id = b.id
    WHERE  t.user_id = ?
    ORDER  BY t.borrow_date DESC
");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My History — YIC Library</title>
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
        <h2 class="page-title">📋 My Borrowing History</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <?php if (empty($history)): ?>
            <div class="alert-warning">
                📭 You haven't borrowed any books yet.
                <a href="browse.php">Browse books →</a>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Title</th><th>Author</th>
                        <th>Borrow Date</th><th>Return Date</th>
                        <th>Fine</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $r): ?>
                    <tr>
                        <td data-label="Title"><?= htmlspecialchars($r['title']) ?></td>
                        <td data-label="Author"><?= htmlspecialchars($r['author']) ?></td>
                        <td data-label="Borrow Date"><?= $r['borrow_date'] ?></td>
                        <td data-label="Return Date">
                            <?= $r['return_date']
                                ? $r['return_date']
                                : '<span style="color:#b23b2c;font-weight:600;">Pending</span>' ?>
                        </td>
                        <td data-label="Fine">
                            <?php if ($r['fine_amount'] > 0): ?>
                                <span style="color:#b23b2c;font-weight:700;">
                                    $<?= number_format($r['fine_amount'], 2) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#2d6a4f;">None</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Action">
                            <?php if (!$r['return_date'] && $r['book_status'] === 'borrowed'): ?>
                                <a href="../actions/return.php?book_id=<?= $r['book_id'] ?>"
                                   class="btn-return"
                                   onclick="return confirm('Return this book?');">
                                   ↩️ Return
                                </a>
                            <?php else: ?>
                                <span style="color:#2d6a4f;font-size:13px;">✅ Returned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>
    <a href="browse.php" class="back-link">← Back to Books</a>
</main>

<footer><p>&copy; 2026 Yanbu Industrial College — Library System</p></footer>
<script src="../assets/js/script.js"></script>
</body>
</html>
