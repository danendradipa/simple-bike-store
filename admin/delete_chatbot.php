<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM chatbot WHERE id = ?");
$stmt->execute([$id]);

header("Location: view_chatbot.php?delete_success=true");
exit();
?>
