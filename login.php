<?php
require_once __DIR__ . '/../back/db.php';

if (isLogged()) {
    header('Location: ../index.php');
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — DarLoc</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="auth-wrap">
  <div class="auth-card">
    <a href="../index.php" class="logo" style="color:var(--text);margin-bottom:18px;">
      <span class="logo-mark">🏠</span> Dar<span style="color:var(--primary)">Loc</span>
    </a>
    <h1>Bon retour 👋</h1>
    <p class="sub">Connectez-vous pour réserver votre prochain logement.</p>

    <?php if ($error): ?>
      <div class="error-msg"><?= e($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="../back/login.php" novalidate>
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="vous@exemple.tn" value="<?= e($_GET['email'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="••••••••">
      </div>
      <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? '') ?>">
      <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="alt">
      Pas encore de compte ? <a href="register.php">Créer un compte</a>
    </div>
  </div>
</div>

<script src="../js/script.js"></script>
</body>
</html>
