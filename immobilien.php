<?php
declare(strict_types=1);
require __DIR__ . '/lib/database.php';
$listings = getAllListings($_GET);
$hasFilters = !empty($_GET['offer']) || !empty($_GET['type']) || !empty(trim((string)($_GET['location'] ?? '')));
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Immobilien zum Kaufen und Mieten in der Schweiz.">
  <title>Immobilien entdecken — re.city</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/immobilien.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/catalogue.css?v=1">
  <link rel="stylesheet" href="assets/css/filters.css?v=1">
  <link rel="stylesheet" href="assets/css/live-filter.css?v=1">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=1">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="index.php" aria-label="re.city Startseite"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav" aria-label="Hauptnavigation">
        <a href="index.php">Home</a>
        <a class="active" href="immobilien.php">Immobilien</a>
        <a href="kontaktiere-uns.php">Kontakt</a>
        <a href="login.php">Anmelden</a>
      </nav>
      <div class="nav-actions">
        <a class="button button-dark" href="insert-property/">Immobilie inserieren <span>↗</span></a>
      </div>
    </div>
  </header>

  <main>
    <section class="properties-hero">
      <p class="eyebrow">Ihr neues Zuhause</p>
      <h1>Alle Immobilien<br><span>auf einen Blick.</span></h1>
      <p>Ausgewählte Häuser und Wohnungen in der ganzen Schweiz – zum Kaufen und Mieten.</p>
    </section>

    <section class="property-filter" aria-label="Immobilien filtern">
      <form method="get">
        <label><span>Angebot</span><select name="offer"><option value="">Kaufen & Mieten</option><option<?= ($_GET['offer'] ?? '') === 'Kaufen' ? ' selected' : '' ?>>Kaufen</option><option<?= ($_GET['offer'] ?? '') === 'Mieten' ? ' selected' : '' ?>>Mieten</option></select></label>
        <label><span>Immobilientyp</span><select name="type"><option value="">Alle Immobilien</option><option<?= ($_GET['type'] ?? '') === 'Wohnung' ? ' selected' : '' ?>>Wohnung</option><option<?= ($_GET['type'] ?? '') === 'Haus' ? ' selected' : '' ?>>Haus</option></select></label>
        <label><span>Suche</span><input type="search" name="location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>" placeholder="Ort oder Suchbegriff"></label>
        <button type="submit">Suchen <span>↗</span></button>
      </form>
    </section>

    <section class="listings section-pad">
      <div class="section-head">
        <div><p class="eyebrow">Aktuelle Angebote</p><h2>Immobilien entdecken</h2></div>
        <div class="results-summary">
          <p class="result-count"><?= count($listings) ?> <?= count($listings) === 1 ? 'Objekt' : 'Objekte' ?></p>
          <?php if ($hasFilters): ?><a href="immobilien.php">Filter zurücksetzen ×</a><?php endif; ?>
        </div>
      </div>
      <?php if ($listings): ?>
      <div class="listing-grid">
        <?php foreach ($listings as $listing): ?>
          <article class="property-card">
            <a class="property-image" href="property.php?id=<?= (int)$listing['id'] ?>" style="background-image:url('<?= htmlspecialchars($listing['image_url']) ?>')">
              <span class="badge"><?= htmlspecialchars($listing['status']) ?></span>
              <span class="save" aria-label="Merken">♡</span>
            </a>
            <div class="property-copy">
              <p class="location"><?= htmlspecialchars($listing['address']) ?></p>
              <h3><a href="property.php?id=<?= (int)$listing['id'] ?>"><?= htmlspecialchars($listing['title']) ?></a></h3>
              <p class="property-description"><?= htmlspecialchars(mb_strimwidth($listing['description'], 0, 145, '…')) ?></p>
              <div class="property-meta"><span><?= number_format((float)$listing['rooms'], 0) ?> Zimmer</span><span><?= (int)$listing['bathrooms'] ?> Badezimmer</span><span><?= (int)$listing['area'] ?> m²</span></div>
              <p class="price">CHF <?= number_format((float)$listing['price'], 0, '.', '’') ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <div class="no-results">
          <p class="eyebrow">Keine Treffer</p>
          <h2>Leider nichts gefunden.</h2>
          <p>Ändern Sie Ihre Filter oder setzen Sie die Suche zurück, um alle Immobilien zu sehen.</p>
          <a href="immobilien.php">Alle Immobilien anzeigen <span>↗</span></a>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-main">
      <div class="footer-brand">
        <a class="brand" href="index.php"><span>re.</span>city</a>
        <p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p>
      </div>
      <div class="footer-links"><p class="footer-label">Entdecken</p><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a></div>
      <div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div>
    </div>
    <div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div>
  </footer>
  <script src="assets/js/app.js?v=2"></script>
</body>
</html>
