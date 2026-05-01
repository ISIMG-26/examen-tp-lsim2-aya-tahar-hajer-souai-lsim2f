<?php
require_once __DIR__ . '/db.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare("DELETE FROM maisons WHERE id = ?")->execute([$id]);
    $_SESSION['admin_success'] = 'Maison supprimée.';
}
header('Location: ../html/admin.php');
exit;
