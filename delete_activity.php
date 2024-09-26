<?php
require 'auth_check.php';
require 'db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
}

header('Location: dashboard.php');
?>
