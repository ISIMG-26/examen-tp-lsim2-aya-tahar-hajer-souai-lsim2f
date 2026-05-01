<?php
// =================================================
// AJAX : recherche / filtrage des maisons → JSON
// =================================================
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

$q        = trim($_GET['q'] ?? '');
$ville    = trim($_GET['ville'] ?? '');
$type     = trim($_GET['type'] ?? '');
$maxPrice = (int)($_GET['maxPrice'] ?? 999999);
$onlyAvail = isset($_GET['available']) && $_GET['available'] === '1';

$sql = "SELECT * FROM maisons WHERE prix <= :max";
$params = [':max' => $maxPrice];

if ($q !== '') {
    $sql .= " AND (titre LIKE :q OR description LIKE :q OR quartier LIKE :q OR ville LIKE :q)";
    $params[':q'] = '%' . $q . '%';
}
if ($ville !== '') { $sql .= " AND ville = :ville"; $params[':ville'] = $ville; }
if ($type !== '')  { $sql .= " AND type = :type";   $params[':type']  = $type;  }
if ($onlyAvail)    { $sql .= " AND disponible = 1"; }

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
