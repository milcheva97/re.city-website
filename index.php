<?php
declare(strict_types=1);
require __DIR__ . '/lib/database.php';

$listings = getFeaturedListings();
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Ausgewählte Immobilien in der Schweiz – modern, persönlich und transparent.">
  <title>re.city — Immobilien neu gedacht</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/hero-motion.css?v=4">
  <link rel="stylesheet" href="assets/css/renderings.css">
  <link rel="stylesheet" href="assets/css/hero-actions.css">
  <link rel="stylesheet" href="assets/css/intro-section.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=1">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="#top" aria-label="re.city Startseite"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav" aria-label="Hauptnavigation">
        <a class="active" href="#top">Home</a>
        <a href="immobilien.php">Immobilien</a>
        <a href="kontaktiere-uns.php">Kontakt</a>
        <a href="login.php">Anmelden</a>
      </nav>
      <div class="nav-actions">
        <a class="button button-dark" href="insert-property/">Immobilie inserieren <span>↗</span></a>
      </div>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="hero-shade"></div>
      <div class="hero-content">
        <p class="eyebrow light">Immobilien. Einfach besser.</p>
        <h1>Räume, in denen<br><em>Leben</em> passiert.</h1>
        <p class="hero-copy">Entdecken Sie handverlesene Immobilien in der ganzen Schweiz – persönlich begleitet, klar präsentiert.</p>
        <div class="hero-actions">
          <a class="round-link" href="#renderings" aria-label="Weiter zum nächsten Bereich">↓</a>
          <a class="hero-property-link" href="immobilien.php">Alle Immobilien entdecken <span>↗</span></a>
        </div>
      </div>
    </section>

    <section class="renderings-section" id="renderings">
      <div class="renderings-heading">
        <p class="eyebrow">Interaktive Erlebnisse</p>
        <h2>Real Estate.<br><span>Real Rendering.</span></h2>
        <p>Entdecken Sie Räume, Gebäude und Destinationen in interaktiven 3D-Welten.</p>
      </div>

      <div class="renderings-grid">
        <?php
        $renderings = [
          ['Firenze Fiera', 'Florenz · Italien', 'https://archit.ooo/demo/firenzefiera/index.html'],
          ['Allianz MiCo', 'Mailand · Italien', 'https://viewit.it/public/allianzmico/fm/index.html?g=it&l=0&l1=0'],
          ['Rimini Fiera', 'Rimini · Italien', 'https://archit.ooo/demo/riminifiera/index.html'],
          ['Rimini Palacongressi', 'Rimini · Italien', 'https://archit.ooo/demo/riminipala/index.html'],
          ['VICC', 'Vicenza · Italien', 'https://viewit.it/public/vicc/vc/index.html?g=it&l=0&l1=0'],
          ['Bergamo Fiera', 'Bergamo · Italien', 'https://viewit.it/public/bergamofiera/bf/index.html?g=it&l=0&l1=0'],
          ['Riva del Garda', 'Trentino · Italien', 'https://archit.ooo/demo/riva/index.html'],
          ['Orazio Residence', 'Interaktive Besichtigung', 'https://www.viewit.it/demo/080/orazio/4/index.html'],
          ['Poiano Resort', 'Gardasee · Italien', 'https://viewit.it/public/poiano/pr/index.html?g=it&l=0&l1=0'],
          ['Stilcasa', 'Interaktive Besichtigung', 'https://www.viewit.it/demo/stilcasa/3/demo.html'],
          ['Luxury Yacht', 'Virtuelle Tour', 'https://www.viewit.it/demo/080/yacht/index.html'],
        ];
        foreach ($renderings as $index => [$title, $place, $url]):
          $hidden = $index >= 3;
        ?>
          <article class="rendering-card<?= $hidden ? ' rendering-extra' : '' ?>"<?= $hidden ? ' hidden' : '' ?>>
            <div class="rendering-frame">
              <iframe
                <?= $hidden ? 'data-src' : 'src' ?>="<?= htmlspecialchars($url) ?>"
                title="<?= htmlspecialchars($title) ?> – interaktive 3D-Ansicht"
                loading="lazy"
                allow="fullscreen; accelerometer; gyroscope"
                referrerpolicy="strict-origin-when-cross-origin"></iframe>
              <a class="rendering-open" href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener" aria-label="<?= htmlspecialchars($title) ?> in neuem Tab öffnen">↗</a>
            </div>
            <div class="rendering-info">
              <div><p><?= htmlspecialchars($place) ?></p><h3><?= htmlspecialchars($title) ?></h3></div>
              <span><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="renderings-action">
        <button class="show-renderings" type="button" aria-expanded="false">
          <span>Mehr anzeigen</span><i>↓</i>
        </button>
      </div>
    </section>

    <section class="intro section-pad">
      <p class="eyebrow">Ein neuer Blick auf Immobilien</p>
      <div class="intro-grid">
        <h2>Nicht einfach vier Wände.<br><span>Sondern Ihr nächstes Kapitel.</span></h2>
        <div class="intro-side">
          <p>Wir verbinden moderne Technologie mit echter Beratung. So wird die Suche nach einem neuen Zuhause übersichtlich, inspirierend und vor allem: persönlich.</p>
          <a class="intro-contact" href="kontaktiere-uns.php">Kontakt <span>↗</span></a>
        </div>
      </div>
    </section>

    <section class="service section-pad" id="service">
      <div class="service-image"><span class="image-caption">Zuhören. Verstehen. Finden.</span></div>
      <div class="service-copy">
        <p class="eyebrow">An Ihrer Seite</p>
        <h2>Immobilienberatung,<br>die sich <em>menschlich</em> anfühlt.</h2>
        <p>Ob erster Kauf, nächster Umzug oder erfolgreicher Verkauf: Wir begleiten Sie mit Marktkenntnis, Klarheit und einem offenen Ohr.</p>
        <div class="benefits">
          <div><span>01</span><h3>Schnell finden</h3><p>Intelligente Suche, relevante Ergebnisse.</p></div>
          <div><span>02</span><h3>Persönlich beraten</h3><p>Direkte Ansprechpartner statt Callcenter.</p></div>
          <div><span>03</span><h3>Sicher entscheiden</h3><p>Transparente Daten und klare Prozesse.</p></div>
        </div>
      </div>
    </section>

    <section class="cta" id="kontakt">
      <p class="eyebrow light">Ihre Immobilie. Unsere Expertise.</p>
      <h2>Bereit für den<br><em>nächsten Schritt?</em></h2>
      <p>Wir bewerten Ihre Immobilie unverbindlich und zeigen Ihnen, welches Potenzial in ihr steckt.</p>
      <a class="button button-light" href="mailto:info@techede.com?subject=Kostenlose%20Erstberatung">Kostenlose Erstberatung <span>↗</span></a>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-main">
      <div class="footer-brand">
        <a class="brand" href="#top"><span>re.</span>city</a>
        <p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p>
      </div>
      <div class="footer-links">
        <p class="footer-label">Entdecken</p>
        <a href="#top">Home</a>
        <a href="immobilien.php">Immobilien</a>
        <a href="kontaktiere-uns.php">Kontakt</a>
      </div>
      <div class="footer-links">
        <p class="footer-label">Kontakt</p>
        <a href="mailto:info@techede.com">info@techede.com</a>
        <p>Schweiz</p>
      </div>
    </div>
    <div class="footer-lower">
      <div class="footer-bottom">
        <p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p>
        <div><a href="#">Impressum</a><a href="#">Datenschutz</a></div>
      <p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p>
      </div>
    </div>
  </footer>
  <script src="assets/js/app.js"></script>
</body>
</html>
