<?php
// AJAX : vérifier si un email existe déjà (utilisé à l'inscription)
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$email = trim($_GET['email'] ?? '');
if ($email === '') { echo json_encode(['exists' => false]); exit; }

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
echo json_encode(['exists' => (bool)$stmt->fetch()]);
