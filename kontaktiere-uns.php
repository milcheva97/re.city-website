<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/lib/database.php';

$_SESSION['contact_token'] ??= bin2hex(random_bytes(24));
$errors = [];
$sent = false;
$values = ['name'=>'','email'=>'','phone'=>'','topic'=>'Immobilie kaufen','message'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $default) $values[$key] = trim((string)($_POST[$key] ?? $default));
    if (!hash_equals($_SESSION['contact_token'], (string)($_POST['token'] ?? ''))) $errors[] = 'Die Sitzung ist abgelaufen. Bitte versuchen Sie es erneut.';
    if (!empty($_POST['website'])) $errors[] = 'Die Nachricht konnte nicht gesendet werden.';
    if (mb_strlen($values['name']) < 2) $errors[] = 'Bitte geben Sie Ihren Namen ein.';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
    if (mb_strlen(preg_replace('/\D+/', '', $values['phone']) ?? '') < 7) $errors[] = 'Bitte geben Sie eine gültige Telefonnummer ein.';
    if (mb_strlen($values['message']) < 10) $errors[] = 'Bitte beschreiben Sie Ihr Anliegen mit mindestens 10 Zeichen.';
    if (empty($_POST['privacy'])) $errors[] = 'Bitte bestätigen Sie die Datenschutzerklärung.';

    if (!$errors) {
        saveContactMessage($values);
        $sent = true;
        $values = ['name'=>'','email'=>'','phone'=>'','topic'=>'Immobilie kaufen','message'=>''];
        $_SESSION['contact_token'] = bin2hex(random_bytes(24));
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kontaktieren Sie re.city für Immobilienberatung, Besichtigungen und Verkauf.">
  <title>Kontakt — re.city</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/contact.css?v=1">
  <link rel="stylesheet" href="assets/css/contact-details.css?v=3">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=2">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="index.php"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav"><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a class="active" href="kontaktiere-uns.php">Kontakt</a><a href="login.php">Anmelden</a></nav>
      <div class="nav-actions"><a class="button button-dark" href="immobilien.php">Immobilien entdecken <span>↗</span></a></div>
    </div>
  </header>

  <main>
    <section class="contact-hero">
      <p class="eyebrow">Wir sind für Sie da</p>
      <div><h1>Lassen Sie uns über<br><em>Ihr Zuhause</em> sprechen.</h1><p>Ob Sie kaufen, verkaufen oder einfach eine Frage haben – schreiben Sie uns. Wir melden uns persönlich bei Ihnen.</p></div>
    </section>

    <section class="contact-layout">
      <aside class="contact-details">
        <p class="eyebrow">Direkter Kontakt</p>
        <a class="contact-email" href="mailto:info@techede.com">info@techede.com <span>↗</span></a>
        <div class="contact-meta">
          <div><span>Adresse</span><p>Techede International AG<br>Tödistrasse 50<br>8810 Horgen</p></div>
          <div><span>Telefon</span><p><a href="tel:+41448229000">044 822 90 00</a></p><span>Antwortzeit</span><p>In der Regel innerhalb<br>eines Werktages</p></div>
        </div>
        <p class="contact-note">Sie interessieren sich für eine konkrete Immobilie? Nennen Sie uns bitte den Titel oder die Objekt-ID.</p>
      </aside>

      <div class="contact-form-wrap">
        <?php if ($sent): ?>
          <div class="form-success"><span>✓</span><p class="eyebrow">Nachricht erhalten</p><h2>Vielen Dank.</h2><p>Ihre Anfrage wurde gespeichert. Wir melden uns so bald wie möglich bei Ihnen.</p><a href="immobilien.php">Immobilien entdecken ↗</a></div>
        <?php else: ?>
          <div class="form-heading"><p class="eyebrow">Ihre Anfrage</p><h2>Wie können wir helfen?</h2></div>
          <?php if ($errors): ?><div class="form-errors" role="alert"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
          <form class="contact-form" method="post" novalidate>
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['contact_token']) ?>">
            <label class="honeypot" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label>
            <div class="form-row">
              <label><span>Name *</span><input name="name" value="<?= htmlspecialchars($values['name']) ?>" required autocomplete="name" placeholder="Ihr Vor- und Nachname"></label>
              <label><span>E-Mail *</span><input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>" required autocomplete="email" placeholder="name@beispiel.ch"></label>
            </div>
            <div class="form-row">
              <label><span>Telefon *</span><input type="tel" name="phone" value="<?= htmlspecialchars($values['phone']) ?>" required autocomplete="tel" placeholder="+41 ..."></label>
              <label><span>Worum geht es?</span><select name="topic"><option<?= $values['topic']==='Immobilie kaufen'?' selected':'' ?>>Immobilie kaufen</option><option<?= $values['topic']==='Immobilie verkaufen'?' selected':'' ?>>Immobilie verkaufen</option><option<?= $values['topic']==='Besichtigung'?' selected':'' ?>>Besichtigung</option><option<?= $values['topic']==='Allgemeine Anfrage'?' selected':'' ?>>Allgemeine Anfrage</option></select></label>
            </div>
            <label><span>Nachricht *</span><textarea name="message" required rows="6" placeholder="Erzählen Sie uns kurz von Ihrem Anliegen ..."><?= htmlspecialchars($values['message']) ?></textarea></label>
            <label class="privacy-check"><input type="checkbox" name="privacy" value="1"><span>Ich stimme der Verarbeitung meiner Angaben zur Bearbeitung der Anfrage zu.</span></label>
            <button type="submit">Nachricht senden <span>↗</span></button>
          </form>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-main"><div class="footer-brand"><a class="brand" href="index.php"><span>re.</span>city</a><p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p></div><div class="footer-links"><p class="footer-label">Entdecken</p><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a></div><div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div></div>
    <div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div>
  </footer>
  <script src="assets/js/app.js?v=2"></script>
</body>
</html>
