<?php
declare(strict_types=1);
require __DIR__ . '/lib/database.php';

$listing = getListingById((int)($_GET['id'] ?? 0));
if (!$listing) {
    http_response_code(404);
    $listing = null;
}
$similar = $listing ? getSimilarListings((int)$listing['id']) : [];
$galleryImages = $listing ? [$listing['image_url']] : [];
if ($listing && str_contains($listing['title'], 'S. Antonino')) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/sant-antonino*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 156) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf156m2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 119) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf119m2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 86) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf86m2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 70) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf70m2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 141) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf86m2-2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
if ($listing && $listing['city'] === 'Schinznach-Dorf' && (int)$listing['area'] === 135) {
    $galleryFiles = glob(__DIR__ . '/assets/images/properties/Schinznach-Dorf70m2-2-*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    natsort($galleryFiles);
    $galleryImages = array_values(array_map(
        fn(string $file): string => 'assets/images/properties/' . basename($file),
        $galleryFiles
    ));
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $listing ? htmlspecialchars($listing['title']) . ' — re.city' : 'Immobilie nicht gefunden — re.city' ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/property.css?v=1">
  <link rel="stylesheet" href="assets/css/property-gallery.css?v=1">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=1">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="index.php"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav"><a href="index.php">Home</a><a class="active" href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a><a href="login.php">Anmelden</a></nav>
      <div class="nav-actions"><a class="button button-dark" href="mailto:info@techede.com">Besichtigung anfragen <span>↗</span></a></div>
    </div>
  </header>

  <?php if (!$listing): ?>
    <main class="not-found"><p class="eyebrow">404</p><h1>Immobilie nicht gefunden.</h1><a href="immobilien.php">Zurück zu den Immobilien ↗</a></main>
  <?php else: ?>
  <main>
    <section class="property-title">
      <a class="back-link" href="immobilien.php">← Alle Immobilien</a>
      <div class="property-title-grid">
        <div><p class="eyebrow"><?= htmlspecialchars($listing['status']) ?></p><h1><?= htmlspecialchars($listing['title']) ?></h1><p class="detail-address"><?= htmlspecialchars($listing['address']) ?></p></div>
        <div class="detail-price"><span>Kaufpreis</span><strong>CHF <?= number_format((float)$listing['price'], 0, '.', '’') ?></strong></div>
      </div>
    </section>

    <section class="property-gallery<?= count($galleryImages) > 1 ? ' property-gallery-mosaic' : '' ?>">
      <?php foreach (array_slice($galleryImages, 0, count($galleryImages) > 1 ? 5 : 1) as $index => $image): ?>
        <button class="gallery-tile gallery-tile-<?= $index + 1 ?>" type="button" data-gallery-index="<?= $index ?>" aria-label="Foto <?= $index + 1 ?> öffnen">
          <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($listing['title']) ?> – Foto <?= $index + 1 ?>">
        </button>
      <?php endforeach; ?>
      <?php if (count($galleryImages) > 1): ?>
        <button class="gallery-show-all" type="button" data-gallery-open>Alle <?= count($galleryImages) ?> Fotos <span>▦</span></button>
      <?php else: ?>
        <div class="gallery-count"><span>01</span> / 01</div>
      <?php endif; ?>
    </section>

    <?php if (count($galleryImages) > 1): ?>
      <dialog class="gallery-dialog" aria-label="Bildergalerie">
        <div class="gallery-dialog-head"><p><?= htmlspecialchars($listing['title']) ?> · <?= count($galleryImages) ?> Fotos</p><button type="button" data-gallery-close aria-label="Galerie schließen">×</button></div>
        <div class="gallery-dialog-grid">
          <?php foreach ($galleryImages as $index => $image): ?>
            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($listing['title']) ?> – Foto <?= $index + 1 ?>" loading="<?= $index > 5 ? 'lazy' : 'eager' ?>">
          <?php endforeach; ?>
        </div>
      </dialog>
    <?php endif; ?>

    <section class="property-overview">
      <div class="key-facts">
        <div><span>Zimmer</span><strong><?= number_format((float)$listing['rooms'], 0) ?></strong></div>
        <div><span>Badezimmer</span><strong><?= (int)$listing['bathrooms'] ?></strong></div>
        <div><span>Wohnfläche</span><strong><?= (int)$listing['area'] ?> m²</strong></div>
        <div><span>Objekttyp</span><strong><?= htmlspecialchars($listing['property_type']) ?></strong></div>
      </div>
      <aside class="viewing-box">
        <p class="eyebrow">Persönlich erleben</p><h2>Diese Immobilie könnte Ihr neues Zuhause sein.</h2>
        <a href="mailto:info@techede.com?subject=Besichtigung%20<?= rawurlencode($listing['title']) ?>">Eine Besichtigung anfragen <span>↗</span></a>
      </aside>
    </section>

    <section class="property-content">
      <div class="description-block"><p class="eyebrow">Über die Immobilie</p><h2>Beschreibung</h2><p><?= nl2br(htmlspecialchars($listing['description'])) ?></p><p>Gerne stellen wir Ihnen weitere Unterlagen zur Verfügung und begleiten Sie persönlich durch den gesamten Besichtigungs- und Kaufprozess.</p></div>
      <div class="specification-block">
        <p class="eyebrow">Details</p><h2>Spezifikation</h2>
        <dl>
          <div><dt>Objekt-ID</dt><dd><?= str_pad((string)$listing['id'], 4, '0', STR_PAD_LEFT) ?></dd></div>
          <div><dt>Angebot</dt><dd><?= htmlspecialchars($listing['offer_type']) ?></dd></div>
          <div><dt>Immobilientyp</dt><dd><?= htmlspecialchars($listing['property_type']) ?></dd></div>
          <div><dt>Zimmer</dt><dd><?= number_format((float)$listing['rooms'], 0) ?></dd></div>
          <div><dt>Badezimmer</dt><dd><?= (int)$listing['bathrooms'] ?></dd></div>
          <div><dt>Wohnfläche</dt><dd><?= (int)$listing['area'] ?> m²</dd></div>
          <div><dt>Standort</dt><dd><?= htmlspecialchars($listing['canton']) ?></dd></div>
          <div><dt>Status</dt><dd><?= htmlspecialchars($listing['status']) ?></dd></div>
        </dl>
      </div>
    </section>

    <section class="location-block"><p class="eyebrow">Standort</p><div><h2><?= htmlspecialchars($listing['city']) ?></h2><p><?= htmlspecialchars($listing['address']) ?></p><a href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode($listing['address']) ?>" target="_blank" rel="noopener">Auf Google Maps öffnen ↗</a></div></section>

    <section class="similar-properties">
      <div class="similar-heading"><div><p class="eyebrow">Das könnte Ihnen auch gefallen</p><h2>Ähnliche Immobilien</h2></div><a href="immobilien.php">Alle anzeigen ↗</a></div>
      <div class="similar-grid">
        <?php foreach ($similar as $item): ?>
          <article><a class="similar-image" href="property.php?id=<?= (int)$item['id'] ?>" style="background-image:url('<?= htmlspecialchars($item['image_url']) ?>')"></a><p><?= htmlspecialchars($item['address']) ?></p><h3><a href="property.php?id=<?= (int)$item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a></h3><div><span><?= number_format((float)$item['rooms'], 0) ?> Zimmer · <?= (int)$item['area'] ?> m²</span><strong>CHF <?= number_format((float)$item['price'], 0, '.', '’') ?></strong></div></article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <?php endif; ?>

  <footer class="site-footer">
    <div class="footer-main"><div class="footer-brand"><a class="brand" href="index.php"><span>re.</span>city</a><p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p></div><div class="footer-links"><p class="footer-label">Entdecken</p><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a></div><div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div></div>
    <div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div>
  </footer>
  <script src="assets/js/app.js?v=2"></script>
  <script src="assets/js/property-gallery.js?v=1"></script>
</body>
</html>
