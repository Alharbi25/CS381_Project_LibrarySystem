<?php
session_start();
require_once '../includes/config.php';

// Admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_books.php?msg=Book deleted successfully!");
    } catch (PDOException $e) {
        header("Location: manage_books.php?error=Cannot delete: this book has borrowing records.");
    }
} else {
    header("Location: manage_books.php");
}
exit();
?>
