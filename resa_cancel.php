<?php
require_once __DIR__ . '/db.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);

// Vérifier que la résa appartient à l'utilisateur
$stmt = $pdo->prepare("SELECT maison_id FROM reservations WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$resa = $stmt->fetch();

if ($resa) {
    $pdo->prepare("UPDATE reservations SET statut = 'annulee' WHERE id = ?")->execute([$id]);
    // Re-rendre la maison disponible
    $pdo->prepare("UPDATE maisons SET disponible = 1 WHERE id = ?")->execute([$resa['maison_id']]);
    $_SESSION['resa_success'] = 'Réservation annulée avec succès.';
}
header('Location: ../html/mes-reservations.php');
exit;
