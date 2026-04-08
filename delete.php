<?php
require_once 'database.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: index.php?msg=deleted");
} else {
    header("Location: index.php?msg=error");
}
exit();
?>