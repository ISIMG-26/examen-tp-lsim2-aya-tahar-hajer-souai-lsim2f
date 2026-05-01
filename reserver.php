<?php
require_once __DIR__ . '/db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../index.php'); exit; }

$maison_id  = (int)($_POST['maison_id'] ?? 0);
$date_debut = $_POST['date_debut'] ?? '';
$date_fin   = $_POST['date_fin'] ?? '';

// Vérifications
if (!$maison_id || !$date_debut || !$date_fin) {
    $_SESSION['resa_success'] = '';
    header('Location: ../html/details.php?id=' . $maison_id); exit;
}
if (strtotime($date_fin) <= strtotime($date_debut)) {
    header('Location: ../html/details.php?id=' . $maison_id); exit;
}

// Vérifier disponibilité maison
$stmt = $pdo->prepare("SELECT disponible FROM maisons WHERE id = ?");
$stmt->execute([$maison_id]);
$m = $stmt->fetch();
if (!$m || !$m['disponible']) {
    header('Location: ../html/details.php?id=' . $maison_id); exit;
}

// INSERT réservation
$stmt = $pdo->prepare("
  INSERT INTO reservations (user_id, maison_id, date_debut, date_fin, statut)
  VALUES (?, ?, ?, ?, 'en_attente')
");
$stmt->execute([$_SESSION['user_id'], $maison_id, $date_debut, $date_fin]);

// Marquer maison comme réservée
$pdo->prepare("UPDATE maisons SET disponible = 0 WHERE id = ?")->execute([$maison_id]);

$_SESSION['resa_success'] = 'Votre demande de réservation a été enregistrée. Le propriétaire vous contactera sous 24h.';
header('Location: ../html/mes-reservations.php');
exit;
