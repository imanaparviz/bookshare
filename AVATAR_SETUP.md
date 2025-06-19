# Avatar Upload Setup - Dokumentation

## ✅ Implementierte Features

### 🔐 Benutzer-Authentifizierung (Laravel Breeze)

-   **Login/Registrierung**: Voll funktionsfähig unter `/login` und `/register`
-   **Dashboard**: Verfügbar unter `/dashboard` für authentifizierte Benutzer
-   **E-Mail-Verifizierung**: Optional verfügbar

### 👤 Profil-Bearbeitung mit Avatar-Upload

-   **Profil-Seite**: Verfügbar unter `/profile`
-   **Avatar-Upload**: Benutzer können Profilbilder hochladen (max. 2MB)
-   **Avatar-Anzeige**: Avatare werden in der Navigation und im Profil angezeigt
-   **Validierung**: Nur Bilder (PNG, JPG, JPEG, GIF, WebP) erlaubt

## 🚀 Setup-Schritte (bereits durchgeführt)

### 1. Laravel Breeze Installation

```bash
composer require laravel/breeze --dev
php artisan breeze:install # (Blade with Alpine ausgewählt)
npm install && npm run dev
```

### 2. Datenbank-Migration für Avatar

```bash
php artisan make:migration add_avatar_to_users_table --table=users
php artisan migrate
```

### 3. Storage-Link für öffentliche Dateien

```bash
php artisan storage:link
```

## 📁 Geänderte Dateien

### Backend-Dateien:

-   `app/Http/Controllers/ProfileController.php` - Avatar-Upload-Logik
-   `app/Http/Requests/ProfileUpdateRequest.php` - Avatar-Validierung
-   `app/Models/User.php` - Avatar-Feld zu fillable hinzugefügt
-   `database/migrations/2025_06_19_091942_add_avatar_to_users_table.php` - Avatar-Spalte

### Frontend-Dateien:

-   `resources/views/profile/partials/update-profile-information-form.blade.php` - Avatar-Upload-Formular
-   `resources/views/layouts/navigation.blade.php` - Avatar-Anzeige in Navigation

## 🧪 Testing-Anleitung

### 1. Server starten

```bash
php artisan serve
```

Der Server läuft auf: `http://localhost:8000`

### 2. Registrierung testen

1. Besuche `http://localhost:8000/register`
2. Erstelle einen neuen Account
3. Du wirst automatisch eingeloggt und zum Dashboard weitergeleitet

### 3. Avatar-Upload testen

1. Klicke auf deinen Namen in der oberen rechten Ecke
2. Wähle "Profile" aus dem Dropdown
3. Scrolle zum "Avatar"-Feld
4. Wähle ein Bild aus (max. 2MB, PNG/JPG/JPEG/GIF/WebP)
5. Klicke "Save"
6. Das Avatar sollte nun in der Navigation und im Profil angezeigt werden

### 4. Fehlerbehandlung testen

1. Versuche eine zu große Datei hochzuladen (>2MB)
2. Versuche eine Nicht-Bild-Datei hochzuladen
3. Überprüfe, ob entsprechende Fehlermeldungen angezeigt werden

## 📂 Datei-Struktur

```
storage/app/public/avatars/     # Hochgeladene Avatar-Bilder
public/storage/                 # Symbolischer Link zu storage/app/public
```

## ⚙️ Konfiguration

### Validierungsregeln (ProfileUpdateRequest.php):

-   **Avatar**: Optional, muss ein Bild sein, max. 2MB
-   **Name**: Erforderlich, String, max. 255 Zeichen
-   **E-Mail**: Erforderlich, gültige E-Mail, eindeutig

### Upload-Verzeichnis:

-   Avatare werden in `storage/app/public/avatars/` gespeichert
-   Öffentlich zugänglich über `public/storage/avatars/`

## 🔧 Fehlerbehebung

### Problem: Bilder werden nicht angezeigt

**Lösung**: Überprüfe, ob der Storage-Link existiert:

```bash
php artisan storage:link
```

### Problem: Upload-Fehler

**Lösung**: Überprüfe die PHP-Konfiguration:

-   `upload_max_filesize` sollte mindestens 2M sein
-   `post_max_size` sollte mindestens 2M sein

### Problem: Migrations-Fehler

**Lösung**: Migration manuell ausführen:

```bash
php artisan migrate:refresh
```

## 🎯 Features im Detail

### Avatar-Upload-Funktionalität:

1. **File-Upload**: Verwendung von Laravel's `store()` Methode
2. **Validierung**: Bildformat und Größe werden geprüft
3. **Speicherung**: Automatische Dateinamen-Generierung
4. **Anzeige**: Responsive Darstellung in verschiedenen Größen

### Sicherheit:

-   CSRF-Schutz durch `@csrf` Direktive
-   Datei-Validierung verhindert Upload gefährlicher Dateien
-   Authentifizierung erforderlich für Profil-Zugriff

### User Experience:

-   Avatar-Vorschau beim Hochladen
-   Responsive Design für mobile Geräte
-   Klare Fehlermeldungen bei Problemen
-   Smooth Transitions und Alpine.js für interaktive Elemente

## 📱 Mobile Optimierung

Die Avatar-Upload-Funktionalität ist vollständig responsive:

-   **Desktop**: Avatar in Navigation-Dropdown
-   **Mobile**: Avatar im ausklappbaren Burger-Menü
-   **Tablet**: Automatische Anpassung basierend auf Bildschirmgröße

---

**Status**: ✅ Vollständig implementiert und getestet
**Laravel Version**: 12.x
**Frontend**: Blade Templates mit Alpine.js und Tailwind CSS
