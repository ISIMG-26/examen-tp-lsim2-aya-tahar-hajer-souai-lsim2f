<?php
require_once __DIR__ . '/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../html/admin.php'); exit; }

$id          = (int)($_POST['id'] ?? 0);
$titre       = trim($_POST['titre'] ?? '');
$type        = trim($_POST['type'] ?? '');
$ville       = trim($_POST['ville'] ?? '');
$quartier    = trim($_POST['quartier'] ?? '');
$adresse     = trim($_POST['adresse'] ?? '');
$prix        = (int)($_POST['prix'] ?? 0);
$surface     = (int)($_POST['surface'] ?? 0);
$pieces      = (int)($_POST['pieces'] ?? 1);
$sdb         = (int)($_POST['sdb'] ?? 1);
$image       = trim($_POST['image'] ?? 'house-1.jpg');
$disponible  = (int)($_POST['disponible'] ?? 1);
$description = trim($_POST['description'] ?? '');

if ($titre === '' || $ville === '' || $quartier === '' || $adresse === '' || $prix <= 0 || $surface <= 0) {
    $_SESSION['admin_error'] = 'Tous les champs sont obligatoires et les valeurs numériques doivent être positives.';
    header('Location: ../html/admin.php'); exit;
}

if ($id > 0) {
    // UPDATE
    $stmt = $pdo->prepare("
      UPDATE maisons
      SET titre=?, type=?, ville=?, quartier=?, adresse=?, prix=?, surface=?,
          pieces=?, sdb=?, image=?, disponible=?, description=?
      WHERE id=?
    ");
    $stmt->execute([$titre,$type,$ville,$quartier,$adresse,$prix,$surface,
                    $pieces,$sdb,$image,$disponible,$description,$id]);
    $_SESSION['admin_success'] = 'Maison mise à jour avec succès.';
} else {
    // INSERT
    $stmt = $pdo->prepare("
      INSERT INTO maisons (titre,type,ville,quartier,adresse,prix,surface,pieces,sdb,image,disponible,description)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$titre,$type,$ville,$quartier,$adresse,$prix,$surface,
                    $pieces,$sdb,$image,$disponible,$description]);
    $_SESSION['admin_success'] = 'Maison ajoutée avec succès.';
}

header('Location: ../html/admin.php');
exit;
