<?php
declare(strict_types=1);
session_start();
require dirname(__DIR__) . '/lib/database.php';

$_SESSION['property_token'] ??= bin2hex(random_bytes(24));
$errors = [];
$sent = false;
$submissionId = null;
$values = [
  'title'=>'','offer_type'=>'Kaufen','property_type'=>'Wohnung','address'=>'','city'=>'','canton'=>'ZH',
  'price'=>'','rooms'=>'','bathrooms'=>'','area'=>'','description'=>'','contact_name'=>'','contact_email'=>'','contact_phone'=>''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key=>$default) $values[$key] = trim((string)($_POST[$key] ?? $default));
    if (!hash_equals($_SESSION['property_token'], (string)($_POST['token'] ?? ''))) $errors[] = 'Die Sitzung ist abgelaufen. Bitte versuchen Sie es erneut.';
    if (!empty($_POST['website'])) $errors[] = 'Das Angebot konnte nicht übermittelt werden.';
    if (mb_strlen($values['title']) < 4) $errors[] = 'Bitte geben Sie einen aussagekräftigen Titel ein.';
    if (mb_strlen($values['address']) < 5 || mb_strlen($values['city']) < 2) $errors[] = 'Bitte geben Sie die vollständige Adresse ein.';
    if (!in_array($values['offer_type'], ['Kaufen','Mieten'], true)) $errors[] = 'Bitte wählen Sie die Angebotsart.';
    if (!in_array($values['property_type'], ['Wohnung','Haus','Grundstück','Gewerbe'], true)) $errors[] = 'Bitte wählen Sie den Immobilientyp.';
    if ((float)$values['price'] <= 0 || (float)$values['rooms'] <= 0 || (int)$values['area'] <= 0) $errors[] = 'Preis, Zimmer und Fläche müssen grösser als null sein.';
    if (mb_strlen($values['description']) < 30) $errors[] = 'Die Beschreibung muss mindestens 30 Zeichen enthalten.';
    if (mb_strlen($values['contact_name']) < 2) $errors[] = 'Bitte geben Sie Ihren Namen ein.';
    if (!filter_var($values['contact_email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
    if (mb_strlen(preg_replace('/\D+/', '', $values['contact_phone']) ?? '') < 7) $errors[] = 'Bitte geben Sie eine gültige Telefonnummer ein.';
    if (empty($_POST['privacy'])) $errors[] = 'Bitte bestätigen Sie die Datenschutzerklärung.';

    $imagePath = '';
    if (!empty($_FILES['image']['name'])) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Das Bild konnte nicht hochgeladen werden.';
        } elseif ($_FILES['image']['size'] > 8 * 1024 * 1024) {
            $errors[] = 'Das Bild darf maximal 8 MB gross sein.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image']['tmp_name']);
            $extensions = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
            if (!isset($extensions[$mime])) {
                $errors[] = 'Erlaubt sind JPG-, PNG- und WebP-Bilder.';
            } else {
                $uploadDir = dirname(__DIR__) . '/uploads/properties';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
                $filename = bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . '/' . $filename)) $imagePath = 'uploads/properties/' . $filename;
                else $errors[] = 'Das Bild konnte nicht gespeichert werden.';
            }
        }
    }

    if (!$errors) {
        $submissionId = savePropertySubmission([
          'title'=>$values['title'],'offer_type'=>$values['offer_type'],'property_type'=>$values['property_type'],
          'address'=>$values['address'],'city'=>$values['city'],'canton'=>$values['canton'],'price'=>(int)$values['price'],
          'rooms'=>(float)$values['rooms'],'bathrooms'=>(int)$values['bathrooms'],'area'=>(int)$values['area'],
          'description'=>$values['description'],'contact_name'=>$values['contact_name'],'contact_email'=>$values['contact_email'],
          'contact_phone'=>$values['contact_phone'],'image_path'=>$imagePath
        ]);
        $sent = true;
        $_SESSION['property_token'] = bin2hex(random_bytes(24));
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Reichen Sie Ihre Immobilie bei re.city ein."><title>Immobilie inserieren — re.city</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css"><link rel="stylesheet" href="../assets/css/footer.css?v=3"><link rel="stylesheet" href="../assets/css/footer-simple.css?v=6"><link rel="stylesheet" href="../assets/css/header-dark.css?v=3"><link rel="stylesheet" href="../assets/css/brand-theme.css?v=1"><link rel="stylesheet" href="../assets/css/header-typography.css?v=1"><link rel="stylesheet" href="../assets/css/insert-property.css?v=1">
</head>
<body>
  <header class="site-header" id="top"><div class="nav-wrap"><a class="brand" href="../index.php"><span>re.</span>city</a><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span></button><nav id="main-nav"><a href="../index.php">Home</a><a href="../immobilien.php">Immobilien</a><a href="../kontaktiere-uns.php">Kontakt</a><a href="../login.php">Anmelden</a></nav><div class="nav-actions"><a class="button button-dark" href="../immobilien.php">Immobilien entdecken <span>↗</span></a></div></div></header>

  <main>
    <section class="insert-hero"><p class="eyebrow light">Ihre Immobilie. Unsere Reichweite.</p><div><h1>Immobilie<br><em>inserieren.</em></h1><p>Teilen Sie uns die wichtigsten Eckdaten mit. Wir prüfen Ihre Angaben persönlich und melden uns bei Ihnen.</p></div></section>
    <section class="insert-layout">
      <aside><p class="eyebrow">So funktioniert es</p><ol><li><span>01</span><div><h3>Details einreichen</h3><p>Füllen Sie das Formular mit den wichtigsten Informationen aus.</p></div></li><li><span>02</span><div><h3>Persönliche Prüfung</h3><p>Unser Team prüft Ihr Angebot und kontaktiert Sie.</p></div></li><li><span>03</span><div><h3>Professionell vermarkten</h3><p>Nach Ihrer Freigabe wird die Immobilie veröffentlicht.</p></div></li></ol><p class="insert-help">Fragen? <a href="mailto:info@techede.com">info@techede.com</a><br><a href="tel:+41448229000">044 822 90 00</a></p></aside>
      <div class="insert-form-panel">
        <?php if ($sent): ?>
          <div class="insert-success"><span>✓</span><p class="eyebrow">Angebot erhalten</p><h2>Vielen Dank.</h2><p>Ihre Immobilie wurde unter der Referenz <strong>#<?= (int)$submissionId ?></strong> zur Prüfung eingereicht. Wir melden uns persönlich bei Ihnen.</p><a href="../immobilien.php">Immobilien entdecken ↗</a></div>
        <?php else: ?>
          <div class="insert-heading"><p class="eyebrow">Immobiliendaten</p><h2>Erzählen Sie uns mehr.</h2></div>
          <?php if ($errors): ?><div class="insert-errors" role="alert"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['property_token']) ?>"><label class="insert-honeypot">Website<input name="website" tabindex="-1"></label>
            <fieldset><legend>01 · Angebot</legend><label class="full"><span>Titel *</span><input name="title" value="<?= htmlspecialchars($values['title']) ?>" required placeholder="z. B. Lichtdurchflutete Wohnung in Zürich"></label><label><span>Angebotsart *</span><select name="offer_type"><option<?= $values['offer_type']==='Kaufen'?' selected':'' ?>>Kaufen</option><option<?= $values['offer_type']==='Mieten'?' selected':'' ?>>Mieten</option></select></label><label><span>Immobilientyp *</span><select name="property_type"><option<?= $values['property_type']==='Wohnung'?' selected':'' ?>>Wohnung</option><option<?= $values['property_type']==='Haus'?' selected':'' ?>>Haus</option><option<?= $values['property_type']==='Grundstück'?' selected':'' ?>>Grundstück</option><option<?= $values['property_type']==='Gewerbe'?' selected':'' ?>>Gewerbe</option></select></label></fieldset>
            <fieldset><legend>02 · Standort</legend><label class="full"><span>Adresse *</span><input name="address" value="<?= htmlspecialchars($values['address']) ?>" required placeholder="Strasse und Hausnummer"></label><label><span>Ort *</span><input name="city" value="<?= htmlspecialchars($values['city']) ?>" required placeholder="Ort"></label><label><span>Kanton *</span><input name="canton" value="<?= htmlspecialchars($values['canton']) ?>" required maxlength="2" placeholder="ZH"></label></fieldset>
            <fieldset><legend>03 · Eckdaten</legend><label><span>Preis in CHF *</span><input type="number" name="price" value="<?= htmlspecialchars($values['price']) ?>" required min="1" placeholder="850000"></label><label><span>Zimmer *</span><input type="number" name="rooms" value="<?= htmlspecialchars($values['rooms']) ?>" required min="0.5" step="0.5" placeholder="4.5"></label><label><span>Badezimmer</span><input type="number" name="bathrooms" value="<?= htmlspecialchars($values['bathrooms']) ?>" min="0" placeholder="2"></label><label><span>Wohnfläche m² *</span><input type="number" name="area" value="<?= htmlspecialchars($values['area']) ?>" required min="1" placeholder="120"></label><label class="full"><span>Beschreibung *</span><textarea name="description" rows="6" required placeholder="Beschreiben Sie Lage, Ausstattung und Besonderheiten ..."><?= htmlspecialchars($values['description']) ?></textarea></label><label class="full upload-field"><span>Titelbild · JPG, PNG oder WebP, max. 8 MB</span><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label></fieldset>
            <fieldset><legend>04 · Kontakt</legend><label><span>Name *</span><input name="contact_name" value="<?= htmlspecialchars($values['contact_name']) ?>" required autocomplete="name"></label><label><span>E-Mail *</span><input type="email" name="contact_email" value="<?= htmlspecialchars($values['contact_email']) ?>" required autocomplete="email"></label><label class="full"><span>Telefon *</span><input type="tel" name="contact_phone" value="<?= htmlspecialchars($values['contact_phone']) ?>" required autocomplete="tel"></label></fieldset>
            <label class="insert-privacy"><input type="checkbox" name="privacy" value="1"><span>Ich stimme der Verarbeitung meiner Angaben zur Prüfung und Vermarktung der Immobilie zu.</span></label><button type="submit">Immobilie einreichen <span>↗</span></button>
          </form>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="site-footer"><div class="footer-main"><div class="footer-brand"><a class="brand" href="../index.php"><span>re.</span>city</a><p>Immobilien neu gedacht.<br>In der Schweiz zuhause.</p></div><div class="footer-links"><p class="footer-label">Entdecken</p><a href="../index.php">Home</a><a href="../immobilien.php">Immobilien</a><a href="../kontaktiere-uns.php">Kontakt</a></div><div class="footer-links"><p class="footer-label">Kontakt</p><a href="mailto:info@techede.com">info@techede.com</a><p>Schweiz</p></div></div><div class="footer-lower"><div class="footer-bottom"><p>© <?= date('Y') ?> re.city. Alle Rechte vorbehalten.</p><div><a href="#">Impressum</a><a href="#">Datenschutz</a></div><p class="footer-credit">Powered with love <span>♥</span> by <a href="https://ei.one" target="_blank" rel="noopener"><strong>Ei.one</strong></a></p></div></div></footer>
  <script src="../assets/js/app.js?v=2"></script>
</body>
</html>
