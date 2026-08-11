<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/lib/database.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: immobilien.php');
    exit;
}

$_SESSION['login_token'] ??= bin2hex(random_bytes(24));
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!hash_equals($_SESSION['login_token'], (string)($_POST['token'] ?? ''))) {
        $error = 'Die Sitzung ist abgelaufen. Bitte versuchen Sie es erneut.';
    } elseif (!empty($_POST['website'])) {
        $error = 'Die Anmeldung konnte nicht ausgeführt werden.';
    } else {
        $user = findUserByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: immobilien.php');
            exit;
        }
        $error = 'E-Mail-Adresse oder Passwort ist nicht korrekt.';
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Melden Sie sich bei Ihrem re.city Konto an.">
  <title>Anmelden — re.city</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/login.css?v=1">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=2">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
  <link rel="stylesheet" href="assets/css/login-mobile.css?v=2">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="index.php"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav"><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a><a class="active" href="login.php">Anmelden</a></nav>
      <div class="nav-actions"><a class="button button-dark" href="immobilien.php">Immobilien entdecken <span>↗</span></a></div>
    </div>
  </header>

  <main class="login-page">
    <section class="login-visual"><div><p class="eyebrow light">Willkommen zurück</p><h1>Ihr Zuhause.<br><em>Ihr Konto.</em></h1><p>Verwalten Sie Ihre gemerkten Immobilien und bleiben Sie mit uns in Kontakt.</p></div></section>
    <section class="login-panel">
      <div class="login-box">
        <p class="eyebrow">Mein re.city</p><h2>Anmelden</h2><p class="login-intro">Geben Sie Ihre Zugangsdaten ein, um fortzufahren.</p>
        <?php if ($error): ?><div class="login-error" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
          <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['login_token']) ?>">
          <label class="login-honeypot">Website<input name="website" tabindex="-1" autocomplete="off"></label>
          <label><span>E-Mail-Adresse</span><input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required autocomplete="email" placeholder="name@beispiel.ch"></label>
          <label><span>Passwort</span><span class="password-field"><input type="password" name="password" required autocomplete="current-password" placeholder="Ihr Passwort"><button type="button" class="password-toggle" aria-label="Passwort anzeigen">Anzeigen</button></span></label>
          <div class="login-options"><label><input type="checkbox" name="remember"> Angemeldet bleiben</label><a href="mailto:info@techede.com?subject=Passwort%20vergessen">Passwort vergessen?</a></div>
          <button class="login-submit" type="submit">Anmelden <span>↗</span></button>
        </form>
        <p class="login-help">Noch kein Konto? <a href="registration.php">Registrieren Sie sich.</a></p>
      </div>
    </section>
  </main>

  <footer class="site-footer"><div class="footer-main"><div class="footer-brand"><a class="brand" href="index.php"><span>re.</span>city</a><p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p></div><div class="footer-links"><p class="footer-label">Entdecken</p><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a></div><div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div></div><div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div></footer>
  <script src="assets/js/app.js?v=2"></script>
  <script src="assets/js/login.js?v=1"></script>
</body>
</html>
