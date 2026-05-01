<?php
require_once __DIR__ . '/back/db.php';

// Charger les maisons (affichage initial avant filtres AJAX)
$stmt = $pdo->query("SELECT * FROM maisons ORDER BY created_at DESC");
$maisons = $stmt->fetchAll();

// Listes pour les filtres
$villes = $pdo->query("SELECT DISTINCT ville FROM maisons ORDER BY ville")->fetchAll(PDO::FETCH_COLUMN);
$types  = ['S+1','S+2','S+3','S+4','Villa','Duplex'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DarLoc — Location de maisons et appartements en Tunisie</title>
  <meta name="description" content="Trouvez et louez studios, appartements S+1, S+2, S+3 et villas dans toute la Tunisie.">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="site-header">
  <div class="container nav-wrap">
    <a href="index.php" class="logo">
      <span class="logo-mark">🏠</span>
      Dar<span>Loc</span>
    </a>
    <nav class="main-nav">
      <a href="#listings">Annonces</a>
      <a href="#listings">Comment ça marche</a>
      <?php if (isLogged()): ?>
        <?php if (isAdmin()): ?>
          <a href="html/admin.php">Tableau de bord</a>
        <?php else: ?>
          <a href="html/mes-reservations.php">Mes réservations</a>
        <?php endif; ?>
        <a href="back/logout.php" class="btn-link">Déconnexion (<?= e($_SESSION['nom']) ?>)</a>
      <?php else: ?>
        <a href="html/login.php">Connexion</a>
        <a href="html/register.php" class="btn-link">Inscription</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="hero-bg" style="background-image:url('images/hero-villa.jpg');"></div>
  <div class="container hero-content">
    <span class="eyebrow">✨ Plus de 1 200 logements vérifiés</span>
    <h1>Trouvez le <em>chez-vous</em><br>qui vous ressemble.</h1>
    <p>Studios, appartements, villas — louez en quelques clics dans toute la Tunisie, au juste prix.</p>
  </div>
</section>

<div class="container">
  <form id="searchForm" class="search-card" autocomplete="off">
    <div class="filters-grid">
      <div class="field">
        <label for="q">🔍 Recherche</label>
        <input type="text" id="q" name="q" placeholder="Mot-clé, quartier...">
      </div>
      <div class="field">
        <label for="ville">📍 Ville</label>
        <select id="ville" name="ville">
          <option value="">Toutes les villes</option>
          <?php foreach ($villes as $v): ?>
            <option value="<?= e($v) ?>"><?= e($v) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="type">🏠 Type</label>
        <select id="type" name="type">
          <option value="">Tous types</option>
          <?php foreach ($types as $t): ?>
            <option value="<?= e($t) ?>"><?= e($t) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn">Rechercher</button>
    </div>
    <div class="advanced">
      <div class="range-wrap">
        <label>
          <span>🎚 Budget maximum</span>
          <strong id="maxPriceLabel">5 000 TND</strong>
        </label>
        <input type="range" id="maxPrice" name="maxPrice" min="500" max="5000" step="100" value="5000">
      </div>
      <label class="toggle">
        <input type="checkbox" id="onlyAvailable" name="onlyAvailable">
        Disponibles uniquement
      </label>
    </div>
  </form>
</div>

<section id="listings" class="listings">
  <div class="container">
    <div class="section-head">
      <div>
        <h2>Logements disponibles</h2>
        <p><span id="resultsCount"><?= count($maisons) ?> résultat<?= count($maisons) > 1 ? 's' : '' ?></span> correspondant à votre recherche</p>
      </div>
    </div>

    <div id="resultsGrid" class="grid">
      <?php foreach ($maisons as $m): ?>
        <article class="card" onclick="window.location.href='html/details.php?id=<?= $m['id'] ?>'">
          <div class="card-img">
            <img src="images/<?= e($m['image']) ?>" alt="<?= e($m['titre']) ?>" loading="lazy">
            <div class="badges">
              <span class="badge"><?= e($m['type']) ?></span>
              <span class="badge <?= $m['disponible'] ? 'green' : 'red' ?>">
                <?= $m['disponible'] ? 'Disponible' : 'Réservée' ?>
              </span>
            </div>
          </div>
          <div class="card-body">
            <h3><?= e($m['titre']) ?></h3>
            <div class="card-loc">📍 <?= e($m['quartier']) ?>, <?= e($m['ville']) ?></div>
            <div class="card-meta">
              <span>📐 <?= (int)$m['surface'] ?> m²</span>
              <span>🛏 <?= (int)$m['pieces'] ?> pièces</span>
            </div>
            <div class="card-foot">
              <div class="price"><?= number_format($m['prix'], 0, ',', ' ') ?><small> TND/mois</small></div>
              <span class="see-more">Voir détails →</span>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="container foot-wrap">
    <span>© 2026 DarLoc — Plateforme de location en Tunisie</span>
    <div class="links">
      <a href="#">Mentions légales</a>
      <a href="#">Confidentialité</a>
    </div>
  </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>
