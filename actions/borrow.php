<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['book_id'])) {
    $book_id = (int) $_GET['book_id'];
    $user_id = (int) $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        $stmt1 = $pdo->prepare("UPDATE books SET status='borrowed' WHERE id=? AND status='available'");
        $stmt1->execute([$book_id]);

        if ($stmt1->rowCount() > 0) {
            $stmt2 = $pdo->prepare("INSERT INTO transactions (user_id, book_id, borrow_date) VALUES (?, ?, CURDATE())");
            $stmt2->execute([$user_id, $book_id]);
            $pdo->commit();
            header("Location: ../student/history.php?msg=Book borrowed successfully!");
        } else {
            $pdo->rollBack();
            header("Location: ../student/browse.php?msg=Sorry, this book is no longer available.");
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header("Location: ../student/browse.php?msg=An error occurred. Please try again.");
    }
} else {
    header("Location: ../student/browse.php");
}
exit();
?>
