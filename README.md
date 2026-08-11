# re.city website

A modern PHP real-estate website with property search, detail pages, galleries, contact forms, registration, login, and local SQLite persistence.

## Requirements

- PHP 8.1 or newer
- PDO SQLite extension
- Fileinfo extension for property image uploads

## Run locally

```powershell
cd "C:\path\to\re.city-website"
C:\xampp\php\php.exe -S localhost:8000
```

Open [http://localhost:8000](http://localhost:8000).

The SQLite database is created automatically in `data/` on first request. Users can create an account through `registration.php`.

## Main pages

- `/` — homepage
- `/immobilien.php` — searchable property catalogue
- `/property.php?id=...` — property detail page
- `/kontaktiere-uns.php` — contact page
- `/login.php` — login
- `/registration.php` — registration
- `/insert-property/` — property submission

User databases and uploaded files are intentionally excluded from Git.
