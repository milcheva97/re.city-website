<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/lib/database.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: immobilien.php');
    exit;
}

$_SESSION['registration_token'] ??= bin2hex(random_bytes(24));
$errors = [];
$values = ['name'=>'','email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim((string)($_POST['name'] ?? ''));
    $values['email'] = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirmation = (string)($_POST['password_confirmation'] ?? '');

    if (!hash_equals($_SESSION['registration_token'], (string)($_POST['token'] ?? ''))) $errors[] = 'Die Sitzung ist abgelaufen. Bitte versuchen Sie es erneut.';
    if (!empty($_POST['website'])) $errors[] = 'Die Registrierung konnte nicht ausgeführt werden.';
    if (mb_strlen($values['name']) < 2) $errors[] = 'Bitte geben Sie Ihren vollständigen Namen ein.';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
    if (strlen($password) < 10 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) $errors[] = 'Das Passwort muss mindestens 10 Zeichen sowie Gross- und Kleinbuchstaben und eine Zahl enthalten.';
    if ($password !== $confirmation) $errors[] = 'Die Passwörter stimmen nicht überein.';
    if (empty($_POST['terms'])) $errors[] = 'Bitte akzeptieren Sie die Datenschutzbestimmungen.';
    if (!$errors && findUserByEmail($values['email'])) $errors[] = 'Für diese E-Mail-Adresse besteht bereits ein Konto.';

    if (!$errors) {
        $user = createUser($values['name'], $values['email'], $password);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: immobilien.php');
            exit;
        }
        $errors[] = 'Das Konto konnte nicht erstellt werden. Bitte versuchen Sie es erneut.';
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Erstellen Sie Ihr persönliches re.city Konto.">
  <title>Registrieren — re.city</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/footer.css?v=3">
  <link rel="stylesheet" href="assets/css/footer-simple.css?v=6">
  <link rel="stylesheet" href="assets/css/header-dark.css?v=3">
  <link rel="stylesheet" href="assets/css/brand-theme.css?v=1">
  <link rel="stylesheet" href="assets/css/header-typography.css?v=1">
  <link rel="stylesheet" href="assets/css/login.css?v=1">
  <link rel="stylesheet" href="assets/css/registration.css?v=2">
  <link rel="stylesheet" href="assets/css/registration-hero.css?v=3">
  <link rel="stylesheet" href="assets/css/registration-mobile.css?v=1">
</head>
<body>
  <header class="site-header" id="top">
    <div class="nav-wrap">
      <a class="brand" href="index.php"><span>re.</span>city</a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button>
      <nav id="main-nav"><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a><a href="login.php">Anmelden</a></nav>
      <div class="nav-actions"><a class="button button-dark" href="immobilien.php">Immobilien entdecken <span>↗</span></a></div>
    </div>
  </header>

  <main class="login-page registration-page">
    <section class="login-visual registration-visual"><div><p class="eyebrow light">Ihr persönlicher Bereich</p><h1>Finden. Merken.<br><em>Ankommen.</em></h1><p>Mit Ihrem re.city Konto behalten Sie interessante Immobilien im Blick und kommen schneller mit uns ins Gespräch.</p></div></section>
    <section class="login-panel registration-panel">
      <div class="login-box">
        <p class="eyebrow">Mein re.city</p><h2>Registrieren</h2><p class="login-intro">Erstellen Sie kostenlos Ihr persönliches Konto.</p>
        <?php if ($errors): ?><div class="login-error" role="alert"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <form method="post">
          <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['registration_token']) ?>">
          <label class="login-honeypot">Website<input name="website" tabindex="-1" autocomplete="off"></label>
          <label><span>Vor- und Nachname</span><input type="text" name="name" value="<?= htmlspecialchars($values['name']) ?>" required autocomplete="name" placeholder="Ihr Name"></label>
          <label><span>E-Mail-Adresse</span><input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>" required autocomplete="email" placeholder="name@beispiel.ch"></label>
          <label><span>Passwort</span><span class="password-field"><input type="password" name="password" required autocomplete="new-password" placeholder="Mindestens 10 Zeichen"><button type="button" class="password-toggle" data-target="password" aria-label="Passwort anzeigen">Anzeigen</button></span></label>
          <p class="password-note">Mindestens 10 Zeichen, Gross- und Kleinbuchstaben sowie eine Zahl.</p>
          <label><span>Passwort bestätigen</span><span class="password-field"><input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Passwort wiederholen"><button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Passwort anzeigen">Anzeigen</button></span></label>
          <label class="registration-terms"><input type="checkbox" name="terms" value="1"><span>Ich akzeptiere die <a href="#">Datenschutzbestimmungen</a> und die Verarbeitung meiner Angaben.</span></label>
          <button class="login-submit" type="submit">Konto erstellen <span>↗</span></button>
        </form>
        <p class="login-help">Sie haben bereits ein Konto? <a href="login.php">Jetzt anmelden.</a></p>
      </div>
    </section>
  </main>

  <footer class="site-footer"><div class="footer-main"><div class="footer-brand"><a class="brand" href="index.php"><span>re.</span>city</a><p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p></div><div class="footer-links"><p class="footer-label">Entdecken</p><a href="index.php">Home</a><a href="immobilien.php">Immobilien</a><a href="kontaktiere-uns.php">Kontakt</a></div><div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div></div><div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div></footer>
  <script src="assets/js/app.js?v=2"></script>
  <script src="assets/js/registration.js?v=1"></script>
</body>
</html>
