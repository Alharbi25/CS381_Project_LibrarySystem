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

        $stmt1 = $pdo->prepare("UPDATE books SET status='available' WHERE id=?");
        $stmt1->execute([$book_id]);

        $stmt2 = $pdo->prepare("UPDATE transactions SET return_date=CURDATE()
                                WHERE user_id=? AND book_id=? AND return_date IS NULL");
        $stmt2->execute([$user_id, $book_id]);

        $pdo->commit();
        header("Location: ../student/history.php?msg=Book returned successfully!");
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        header("Location: ../student/history.php?msg=An error occurred. Please try again.");
    }
} else {
    header("Location: ../student/history.php");
}
exit();
?>
