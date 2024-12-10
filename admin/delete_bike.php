<?php
include '../db.php';
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM bikes WHERE id = ?");
$stmt->execute([$id]);

header("Location: data_sepeda.php?delete_success=true");
exit();
?>
