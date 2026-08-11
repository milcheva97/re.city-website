<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $pdo = new PDO('sqlite:' . $dir . '/recity.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('CREATE TABLE IF NOT EXISTS listings (id INTEGER PRIMARY KEY AUTOINCREMENT,title TEXT NOT NULL,city TEXT NOT NULL,canton TEXT NOT NULL,price INTEGER NOT NULL,rooms REAL NOT NULL,area INTEGER NOT NULL,offer_type TEXT NOT NULL,property_type TEXT NOT NULL,status TEXT NOT NULL,image_url TEXT NOT NULL,featured INTEGER DEFAULT 0,created_at TEXT DEFAULT CURRENT_TIMESTAMP)');

    $columns = array_column($pdo->query('PRAGMA table_info(listings)')->fetchAll(), 'name');
    foreach (['address'=>'TEXT DEFAULT ""','bathrooms'=>'INTEGER DEFAULT 0','description'=>'TEXT DEFAULT ""','source_url'=>'TEXT DEFAULT ""'] as $name=>$definition) {
        if (!in_array($name, $columns, true)) $pdo->exec("ALTER TABLE listings ADD COLUMN $name $definition");
    }
    $pdo->exec('CREATE TABLE IF NOT EXISTS app_meta (meta_key TEXT PRIMARY KEY, meta_value TEXT NOT NULL)');
    $version = $pdo->query("SELECT meta_value FROM app_meta WHERE meta_key='catalogue_version'")->fetchColumn();
    if ($version !== '5') {
        $pdo->beginTransaction();
        $pdo->exec('DELETE FROM listings');
        $description = <<<'TEXT'
Schinznach-Dorf ist die grösste Rebbaugemeinde des Kantons Aargau, ruhig und idyllisch gelegen. Und trotzdem von der grossen Welt nicht abgeschnitten: Mit dem Bus ist man in weniger als 20 Minuten mitten im Zentrum von Brugg und mit dem Auto noch schneller. Für diejenigen, die ab und zu Grossstadtluft schnuppern wollen, ist Downtown Zürich in Reichweite.

Der ruhige Standort der Überbauung Geezhalde AEON liegt an der Bözeneggstrasse 1 im Zentrum von Schinznach-Dorf. In unmittelbarer Nähe findet man alles, was es zum Leben braucht. Von A wie Apotheke über V wie Volg bis hin zu Z wie Zahnarzt. Und über 50 Vereine freuen sich darauf, dass Sie aktiv mitmachen. Wichtig für Familien mit Kindern: Kindergarten, Primarschule und Oberstufe können in Schinznach-Dorf oder im nahegelegenen Veltheim (Sekundarschule) besucht werden.
TEXT;
        $antoninoDescription = <<<'TEXT'
A Sant’Antonino, crocevia strategico nel distretto di Bellinzona proponiamo un superbo progetto residenziale di sei villette indipendenti 4,5 locali alto standing immersa nel verde con vista panoramica e presenta un’architettura moderna e uno stile razionale che si sviluppa su tre livelli.

La residenza è stata creata appositamente in area verde e con unità anche di ampia metratura così da poter ospitare delle famiglie e poter dar loro un ambiente elegante e salubre dove poter crescere al meglio i propri figli.

Le abitazioni si trovano in una posizione unica per quanto riguarda la comodità, nei loro pressi potranno infatti essere facilmente raggiunti le scuole, l’ufficio postale, l’accesso autostradale, e tutti i possibili servizi principali.

Il progetto è composto da ben sei corpi abitativi indipendenti di alto standing. Si potrà quindi scegliere l’unità più adatta alle proprie esigenze da un ricco ventaglio di opportunità.

La residenza è stata progettata così da assicurare uno straordinario stile di vita. I materiali che sono stati scelti sono infatti di alto standing e sarà possibile godere di una grande luminosità grazie alla grande vetrata con accesso diretto sul giardino privato.

Tutte le villette sono state dotate di un bellissimo giardino convenzionale di proprietà che permetterà di rilassarsi all’aria aperta godendosi al meglio le belle stagioni, una soluzione in stile moderno che creerà un ambiente di pregio e di design dove organizzare degli stupendi party all’aperto o anche solo per rilassarsi nella pace della natura.

Per ogni villetta stanno venendo creati due comodi posti auto coperti dove si potrà accedere direttamente al livello della propria unità abitativa.

I prezzi esposti in tabella sono intesi chiavi in mano.

Disponibile una tabella riepilogativa che dettagli ogni unità, qualora si vogliano avere più informazioni riguardo agli oggetti singoli.

LE VILLE NEL VERDE!
TEXT;
        $seed = [
          ['Nuove ville da 4,5 locali - S. Antonino','S. Antonino','TI','Vicolo Nonella 15D, 6592 S. Antonino',880000,3,2,126,'Kaufen','Haus','Zu verkaufen','assets/images/properties/sant-antonino.jpg',$antoninoDescription,'https://re.city/properties/villetta-45-locali/',1],
          ['4.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',998000,4,2,156,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/haus-g_06-4-5-zimmerwohnung/',1],
          ['3.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',810000,3,2,119,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/haus-g_05-3-5-zimmerwohnung/',1],
          ['3.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',665000,3,1,86,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/haus-g_04-3-5-zimmerwohnung/',1],
          ['2.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',555000,2,1,70,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/haus-g_03-2-5-zimmerwohnung/',1],
          ['3.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',735000,2,1,141,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/g_02-3-5-zimmerwohnung/',1],
          ['2.5-Zimmerwohnung','Schinznach-Dorf','AG','Bözeneggstrasse 1, 5107 Schinznach-Dorf',575000,2,1,135,'Kaufen','Wohnung','Zu verkaufen','assets/images/properties/schinznach.jpg',$description,'https://re.city/properties/haus-g-2-5-zimmerwohnung/',1],
        ];
        $stmt=$pdo->prepare('INSERT INTO listings (title,city,canton,address,price,rooms,bathrooms,area,offer_type,property_type,status,image_url,description,source_url,featured) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        foreach ($seed as $row) $stmt->execute($row);
        $pdo->exec("INSERT OR REPLACE INTO app_meta VALUES ('catalogue_version','5')");
        $pdo->commit();
    }
    return $pdo;
}

function getAllListings(array $filters=[]): array
{
    $where=['1=1']; $params=[];
    if (!empty($filters['offer']) && in_array($filters['offer'],['Kaufen','Mieten'],true)) {$where[]='offer_type=:offer';$params['offer']=$filters['offer'];}
    if (!empty($filters['type']) && in_array($filters['type'],['Wohnung','Haus'],true)) {$where[]='property_type=:type';$params['type']=$filters['type'];}
    if (!empty(trim((string)($filters['location'] ?? '')))) {
        $words = preg_split('/\s+/u', trim((string)$filters['location']), -1, PREG_SPLIT_NO_EMPTY);
        foreach (array_slice($words ?: [], 0, 8) as $index => $word) {
            $key = 'search' . $index;
            $where[] = "(title LIKE :$key COLLATE NOCASE OR city LIKE :$key COLLATE NOCASE OR canton LIKE :$key COLLATE NOCASE OR address LIKE :$key COLLATE NOCASE OR description LIKE :$key COLLATE NOCASE OR property_type LIKE :$key COLLATE NOCASE OR offer_type LIKE :$key COLLATE NOCASE)";
            $params[$key] = '%' . $word . '%';
        }
    }
    $stmt=db()->prepare('SELECT * FROM listings WHERE '.implode(' AND ',$where).' ORDER BY id');$stmt->execute($params);return $stmt->fetchAll();
}

function getFeaturedListings(): array { return db()->query('SELECT * FROM listings WHERE featured=1 ORDER BY id LIMIT 4')->fetchAll(); }

function getListingById(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM listings WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

function getSimilarListings(int $excludeId, int $limit = 3): array
{
    $stmt = db()->prepare('SELECT * FROM listings WHERE id != :id ORDER BY id LIMIT :limit');
    $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function saveContactMessage(array $message): void
{
    db()->exec('CREATE TABLE IF NOT EXISTS contact_messages (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT NOT NULL,
      phone TEXT DEFAULT "",
      topic TEXT NOT NULL,
      message TEXT NOT NULL,
      created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');
    $stmt = db()->prepare('INSERT INTO contact_messages (name,email,phone,topic,message) VALUES (:name,:email,:phone,:topic,:message)');
    $stmt->execute($message);
}

function findUserByEmail(string $email): ?array
{
    db()->exec('CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password_hash TEXT NOT NULL,
      created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');
    $stmt = db()->prepare('SELECT * FROM users WHERE lower(email) = lower(:email) LIMIT 1');
    $stmt->execute(['email' => trim($email)]);
    return $stmt->fetch() ?: null;
}

function createUser(string $name, string $email, string $password): ?array
{
    if (findUserByEmail($email)) return null;
    $stmt = db()->prepare('INSERT INTO users (name,email,password_hash) VALUES (:name,:email,:password)');
    $stmt->execute([
      'name' => trim($name),
      'email' => strtolower(trim($email)),
      'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
    return findUserByEmail($email);
}

function savePropertySubmission(array $property): int
{
    db()->exec('CREATE TABLE IF NOT EXISTS property_submissions (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      title TEXT NOT NULL,
      offer_type TEXT NOT NULL,
      property_type TEXT NOT NULL,
      address TEXT NOT NULL,
      city TEXT NOT NULL,
      canton TEXT NOT NULL,
      price INTEGER NOT NULL,
      rooms REAL NOT NULL,
      bathrooms INTEGER NOT NULL,
      area INTEGER NOT NULL,
      description TEXT NOT NULL,
      contact_name TEXT NOT NULL,
      contact_email TEXT NOT NULL,
      contact_phone TEXT NOT NULL,
      image_path TEXT DEFAULT "",
      status TEXT DEFAULT "pending",
      created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');
    $stmt = db()->prepare('INSERT INTO property_submissions (title,offer_type,property_type,address,city,canton,price,rooms,bathrooms,area,description,contact_name,contact_email,contact_phone,image_path) VALUES (:title,:offer_type,:property_type,:address,:city,:canton,:price,:rooms,:bathrooms,:area,:description,:contact_name,:contact_email,:contact_phone,:image_path)');
    $stmt->execute($property);
    return (int)db()->lastInsertId();
}
