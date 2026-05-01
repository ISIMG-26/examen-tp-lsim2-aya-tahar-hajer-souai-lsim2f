<?php
require_once __DIR__ . '/../back/db.php';
requireAdmin();

$success = $_SESSION['admin_success'] ?? '';
$error   = $_SESSION['admin_error'] ?? '';
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

// Maison à éditer ?
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM maisons WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$maisons = $pdo->query("SELECT * FROM maisons ORDER BY created_at DESC")->fetchAll();

// Stats
$nbMaisons = count($maisons);
$nbDispo   = (int)$pdo->query("SELECT COUNT(*) FROM maisons WHERE disponible = 1")->fetchColumn();
$nbResa    = (int)$pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$nbUsers   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord — DarLoc Admin</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="site-header solid">
  <div class="container nav-wrap">
    <a href="../index.php" class="logo">
      <span class="logo-mark">🏠</span> Dar<span>Loc</span>
    </a>
    <nav class="main-nav">
      <a href="../index.php">Site public</a>
      <a href="admin.php">Dashboard</a>
      <a href="../back/logout.php" class="btn-link">Déconnexion</a>
    </nav>
  </div>
</header>

<div class="container admin-wrap">
  <h1 style="font-size:2.2rem;margin-bottom:6px;">Tableau de bord</h1>
  <p style="color:var(--muted);margin-bottom:30px;">Bienvenue <?= e($_SESSION['nom']) ?>.</p>

  <?php if ($success): ?><div class="success-msg"><?= e($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>

  <!-- Stats -->
  <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:36px;">
    <?php foreach ([
      ['🏠','Maisons',$nbMaisons],['✅','Disponibles',$nbDispo],
      ['📅','Réservations',$nbResa],['👥','Clients',$nbUsers]
    ] as $stat): ?>
      <div class="card" style="padding:22px;cursor:default;">
        <div style="font-size:1.6rem;"><?= $stat[0] ?></div>
        <div style="font-size:0.85rem;color:var(--muted);margin-top:6px;"><?= $stat[1] ?></div>
        <div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--primary);">
          <?= $stat[2] ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Form ajout / édition -->
  <div class="auth-card" style="max-width:none;margin-bottom:40px;">
    <h2 style="font-size:1.5rem;margin-bottom:18px;">
      <?= $edit ? '✏️ Modifier la maison' : '➕ Ajouter une nouvelle maison' ?>
    </h2>

    <form id="maisonForm" method="POST" action="../back/maison_save.php" novalidate>
      <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div class="field">
          <label for="titre">Titre</label>
          <input type="text" id="titre" name="titre" value="<?= e($edit['titre'] ?? '') ?>">
        </div>
        <div class="field">
          <label for="type">Type</label>
          <select id="type" name="type">
            <?php foreach (['S+1','S+2','S+3','S+4','Villa','Duplex'] as $t): ?>
              <option value="<?= $t ?>" <?= ($edit['type'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="ville">Ville</label>
          <input type="text" id="ville" name="ville" value="<?= e($edit['ville'] ?? '') ?>">
        </div>
        <div class="field">
          <label for="quartier">Quartier</label>
          <input type="text" id="quartier" name="quartier" value="<?= e($edit['quartier'] ?? '') ?>">
        </div>
        <div class="field" style="grid-column:1/-1;">
          <label for="adresse">Adresse</label>
          <input type="text" id="adresse" name="adresse" value="<?= e($edit['adresse'] ?? '') ?>">
        </div>
        <div class="field">
          <label for="prix">Prix (TND/mois)</label>
          <input type="number" id="prix" name="prix" min="1" value="<?= e($edit['prix'] ?? '') ?>">
        </div>
        <div class="field">
          <label for="surface">Surface (m²)</label>
          <input type="number" id="surface" name="surface" min="1" value="<?= e($edit['surface'] ?? '') ?>">
        </div>
        <div class="field">
          <label for="pieces">Nombre de pièces</label>
          <input type="number" id="pieces" name="pieces" min="1" value="<?= e($edit['pieces'] ?? '1') ?>">
        </div>
        <div class="field">
          <label for="sdb">Salles de bain</label>
          <input type="number" id="sdb" name="sdb" min="1" value="<?= e($edit['sdb'] ?? '1') ?>">
        </div>
        <div class="field">
          <label for="image">Image (nom du fichier dans /images)</label>
          <select id="image" name="image">
            <?php foreach (['house-1.jpg','house-2.jpg','house-3.jpg','house-4.jpg','house-5.jpg','house-6.jpg'] as $img): ?>
              <option value="<?= $img ?>" <?= ($edit['image'] ?? '') === $img ? 'selected' : '' ?>><?= $img ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="disponible">Disponibilité</label>
          <select id="disponible" name="disponible">
            <option value="1" <?= ($edit['disponible'] ?? 1) ? 'selected' : '' ?>>Disponible</option>
            <option value="0" <?= isset($edit) && !$edit['disponible'] ? 'selected' : '' ?>>Réservée</option>
          </select>
        </div>
        <div class="field" style="grid-column:1/-1;">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="4" style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:12px;font-family:inherit;font-size:0.95rem;resize:vertical;"><?= e($edit['description'] ?? '') ?></textarea>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:16px;">
        <button type="submit" class="btn"><?= $edit ? 'Enregistrer' : 'Ajouter la maison' ?></button>
        <?php if ($edit): ?>
          <a href="admin.php" class="btn btn-outline">Annuler</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Liste maisons -->
  <h2 style="font-size:1.6rem;margin-bottom:14px;">📋 Toutes les maisons</h2>
  <?php if (!$maisons): ?>
    <div class="empty"><h3>Aucune maison.</h3><p>Ajoutez-en une avec le formulaire ci-dessus.</p></div>
  <?php else: ?>
    <table class="admin-table">
      <thead><tr>
        <th>Titre</th><th>Type</th><th>Ville</th><th>Prix</th><th>Statut</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php foreach ($maisons as $m): ?>
          <tr>
            <td><strong><?= e($m['titre']) ?></strong></td>
            <td><?= e($m['type']) ?></td>
            <td><?= e($m['ville']) ?></td>
            <td><?= number_format($m['prix'], 0, ',', ' ') ?> TND</td>
            <td>
              <span class="badge <?= $m['disponible'] ? 'green' : 'red' ?>">
                <?= $m['disponible'] ? 'Disponible' : 'Réservée' ?>
              </span>
            </td>
            <td class="row-actions">
              <a href="admin.php?edit=<?= $m['id'] ?>" class="btn btn-outline">Modifier</a>
              <a href="../back/maison_delete.php?id=<?= $m['id'] ?>" class="btn btn-danger"
                 onclick="return confirm('Supprimer cette maison ?');">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<footer class="site-footer">
  <div class="container foot-wrap">
    <span>© 2026 DarLoc — Admin</span>
  </div>
</footer>

<script src="../js/script.js"></script>
</body>
</html>
