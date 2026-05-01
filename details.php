<?php
require_once __DIR__ . '/../back/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM maisons WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($m['titre']) ?> — DarLoc</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="site-header solid">
  <div class="container nav-wrap">
    <a href="../index.php" class="logo">
      <span class="logo-mark">🏠</span> Dar<span>Loc</span>
    </a>
    <nav class="main-nav">
      <a href="../index.php">Annonces</a>
      <?php if (isLogged()): ?>
        <a href="<?= isAdmin() ? 'admin.php' : 'mes-reservations.php' ?>">
          <?= isAdmin() ? 'Dashboard' : 'Mes réservations' ?>
        </a>
        <a href="../back/logout.php" class="btn-link">Déconnexion</a>
      <?php else: ?>
        <a href="login.php">Connexion</a>
        <a href="register.php" class="btn-link">Inscription</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<div class="container detail-wrap">
  <a href="../index.php" style="color:var(--muted);font-size:0.9rem;">← Retour aux annonces</a>

  <div class="detail-grid" style="margin-top:18px;">
    <div class="detail-main-img">
      <img src="../images/<?= e($m['image']) ?>" alt="<?= e($m['titre']) ?>">
    </div>
    <div style="display:grid;gap:14px;">
      <div class="detail-main-img"><img src="../images/<?= e($m['image']) ?>" alt=""></div>
      <div class="detail-main-img"><img src="../images/house-<?= ($m['id'] % 6) + 1 ?>.jpg" alt=""></div>
    </div>
  </div>

  <div class="detail-info">
    <div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge" style="background:var(--sand);"><?= e($m['type']) ?></span>
        <span class="badge <?= $m['disponible'] ? 'green' : 'red' ?>">
          <?= $m['disponible'] ? 'Disponible' : 'Réservée' ?>
        </span>
      </div>
      <h1><?= e($m['titre']) ?></h1>
      <div class="detail-loc">📍 <?= e($m['adresse']) ?>, <?= e($m['ville']) ?></div>

      <div class="specs">
        <div class="spec"><b>Surface</b><span><?= (int)$m['surface'] ?> m²</span></div>
        <div class="spec"><b>Pièces</b><span><?= (int)$m['pieces'] ?></span></div>
        <div class="spec"><b>Salles de bain</b><span><?= (int)$m['sdb'] ?></span></div>
        <div class="spec"><b>Type</b><span><?= e($m['type']) ?></span></div>
      </div>

      <h3>Description</h3>
      <p style="color:var(--muted);line-height:1.7;"><?= nl2br(e($m['description'])) ?></p>

      <h3>Équipements</h3>
      <ul class="amenities">
        <li>Wifi haut débit</li>
        <li>Climatisation</li>
        <li>Cuisine équipée</li>
        <li>Parking</li>
        <li>Eau & électricité incluses</li>
        <li>Sécurité 24/7</li>
      </ul>
    </div>

    <aside class="book-card">
      <div class="price"><?= number_format($m['prix'], 0, ',', ' ') ?><small> TND/mois</small></div>

      <?php if (!$m['disponible']): ?>
        <button class="btn btn-block" disabled style="opacity:.5;cursor:not-allowed;margin-top:18px;">
          Indisponible
        </button>
      <?php elseif (!isLogged()): ?>
        <a href="login.php?redirect=<?= urlencode('html/details.php?id=' . $m['id']) ?>" class="btn btn-block" style="margin-top:18px;">
          Se connecter pour réserver
        </a>
      <?php else: ?>
        <form method="POST" action="../back/reserver.php" style="margin-top:18px;">
          <input type="hidden" name="maison_id" value="<?= (int)$m['id'] ?>">
          <div class="field" style="margin-bottom:12px;">
            <label>Date d'arrivée</label>
            <input type="date" name="date_debut" required min="<?= date('Y-m-d') ?>">
          </div>
          <div class="field" style="margin-bottom:14px;">
            <label>Date de départ</label>
            <input type="date" name="date_fin" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
          </div>
          <button type="submit" class="btn btn-block">Réserver maintenant</button>
        </form>
      <?php endif; ?>

      <p style="text-align:center;margin-top:16px;font-size:0.82rem;color:var(--muted);">
        Aucune commission cachée
      </p>
    </aside>
  </div>
</div>

<footer class="site-footer">
  <div class="container foot-wrap">
    <span>© 2026 DarLoc</span>
    <div class="links"><a href="#">Mentions légales</a></div>
  </div>
</footer>

<script src="../js/script.js"></script>
</body>
</html>
