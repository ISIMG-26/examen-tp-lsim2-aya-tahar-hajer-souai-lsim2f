<?php
require_once __DIR__ . '/../back/db.php';
requireLogin();
if (isAdmin()) { header('Location: admin.php'); exit; }

$stmt = $pdo->prepare("
  SELECT r.*, m.titre, m.ville, m.image, m.prix
  FROM reservations r
  JOIN maisons m ON r.maison_id = m.id
  WHERE r.user_id = ?
  ORDER BY r.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$resas = $stmt->fetchAll();

$success = $_SESSION['resa_success'] ?? '';
unset($_SESSION['resa_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes réservations — DarLoc</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="site-header solid">
  <div class="container nav-wrap">
    <a href="../index.php" class="logo"><span class="logo-mark">🏠</span> Dar<span>Loc</span></a>
    <nav class="main-nav">
      <a href="../index.php">Annonces</a>
      <a href="mes-reservations.php">Mes réservations</a>
      <a href="../back/logout.php" class="btn-link">Déconnexion</a>
    </nav>
  </div>
</header>

<div class="container admin-wrap">
  <h1 style="font-size:2.2rem;margin-bottom:6px;">Mes réservations</h1>
  <p style="color:var(--muted);margin-bottom:30px;">Bonjour <?= e($_SESSION['nom']) ?>, retrouvez ici toutes vos demandes.</p>

  <?php if ($success): ?><div class="success-msg"><?= e($success) ?></div><?php endif; ?>

  <?php if (!$resas): ?>
    <div class="empty">
      <h3>Aucune réservation pour l'instant.</h3>
      <p>Parcourez les <a href="../index.php" style="color:var(--primary);font-weight:600;">annonces disponibles</a> et trouvez votre logement.</p>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead><tr>
        <th>Maison</th><th>Ville</th><th>Du</th><th>Au</th><th>Prix/mois</th><th>Statut</th><th></th>
      </tr></thead>
      <tbody>
        <?php foreach ($resas as $r): ?>
          <tr>
            <td><strong><?= e($r['titre']) ?></strong></td>
            <td><?= e($r['ville']) ?></td>
            <td><?= e($r['date_debut']) ?></td>
            <td><?= e($r['date_fin']) ?></td>
            <td><?= number_format($r['prix'], 0, ',', ' ') ?> TND</td>
            <td>
              <span class="badge <?= $r['statut']==='confirmee'?'green':($r['statut']==='annulee'?'red':'') ?>">
                <?= ucfirst(str_replace('_',' ', $r['statut'])) ?>
              </span>
            </td>
            <td>
              <?php if ($r['statut'] !== 'annulee'): ?>
                <a href="../back/resa_cancel.php?id=<?= $r['id'] ?>" class="btn btn-outline"
                   style="padding:6px 14px;font-size:0.82rem;height:auto;"
                   onclick="return confirm('Annuler cette réservation ?');">Annuler</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<footer class="site-footer">
  <div class="container foot-wrap"><span>© 2026 DarLoc</span></div>
</footer>

</body>
</html>
