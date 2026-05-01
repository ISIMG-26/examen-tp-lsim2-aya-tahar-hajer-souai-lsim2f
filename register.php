<?php
require_once __DIR__ . '/../back/db.php';

if (isLogged()) {
    header('Location: ../index.php');
    exit;
}

$error = $_SESSION['register_error'] ?? '';
$old   = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_error'], $_SESSION['register_old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — DarLoc</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="auth-wrap">
  <div class="auth-card">
    <a href="../index.php" class="logo" style="color:var(--text);margin-bottom:18px;">
      <span class="logo-mark">🏠</span> Dar<span style="color:var(--primary)">Loc</span>
    </a>
    <h1>Créer un compte</h1>
    <p class="sub">Rejoignez DarLoc et trouvez votre logement idéal.</p>

    <?php if ($error): ?>
      <div class="error-msg"><?= e($error) ?></div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="../back/register.php" novalidate>
      <div class="field">
        <label for="nom">Nom complet</label>
        <input type="text" id="nom" name="nom" placeholder="Mohamed Ben Ali" value="<?= e($old['nom'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="vous@exemple.tn" value="<?= e($old['email'] ?? '') ?>">
      </div>
      <div class="field">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Au moins 6 caractères">
      </div>
      <div class="field">
        <label for="confirm">Confirmer le mot de passe</label>
        <input type="password" id="confirm" name="confirm" placeholder="••••••••">
      </div>
      <button type="submit" class="btn">Créer mon compte</button>
    </form>

    <div class="alt">
      Déjà inscrit ? <a href="login.php">Se connecter</a>
    </div>
  </div>
</div>

<script src="../js/script.js"></script>
</body>
</html>
