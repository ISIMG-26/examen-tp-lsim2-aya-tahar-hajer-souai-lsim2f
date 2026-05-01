<?php
// ===========================================
// Connexion à la base de données MySQL
// ===========================================
$host    = 'localhost';
$dbname  = 'darloc';
$user    = 'root';
$pass    = '';     // mot de passe MySQL (vide par défaut sur WAMP/XAMPP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Démarrer la session sur toutes les pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helpers
function isLogged() { return isset($_SESSION['user_id']); }
function isAdmin()  { return isLogged() && $_SESSION['role'] === 'admin'; }
function requireLogin() {
    if (!isLogged()) { header('Location: ../html/login.php'); exit; }
}
function requireAdmin() {
    if (!isAdmin()) { header('Location: ../index.php'); exit; }
}
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
