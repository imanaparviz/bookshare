# 📚 BookShare - Laravel Buchverwaltungssystem

Ein modernes, vollständiges Buchverwaltungssystem gebaut mit Laravel 11, Breeze-Authentifizierung und einem responsiven Design.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## 🚀 Features

### 🔐 Benutzer-Management

-   **Laravel Breeze Authentifizierung** - Login, Registrierung, Passwort-Reset
-   **Avatar-Upload** - Profilbilder mit automatischer Größenanpassung
-   **Profil-Bearbeitung** - Vollständige Benutzerdatenverwaltung
-   **E-Mail-Verifizierung** - Sichere Kontoaktivierung

### 📖 Bücher-Management

-   **CRUD-Operationen** - Erstellen, Lesen, Bearbeiten, Löschen von Büchern
-   **Cover-Upload** - Buchcover hochladen (max. 2MB)
-   **Detaillierte Metadaten** - Titel, Autor, ISBN, Genre, Jahr, Sprache
-   **Status-Tracking** - Verfügbar, Ausgeliehen, Reserviert
-   **Zustandsbewertung** - 4-stufiges Bewertungssystem
-   **Besitzer-Berechtigungen** - Sichere Zugriffskontrolle

### 🎨 Frontend

-   **Responsive Design** - Mobile-first mit Tailwind CSS
-   **Dunkler Modus** - Vollständige Dark-Mode-Unterstützung
-   **Alpine.js** - Interaktive UI-Komponenten
-   **Grid-Layout** - Moderne Buchkarten-Darstellung
-   **Empty States** - Benutzerfreundliche leere Zustände

## 📸 Screenshots

### Dashboard

Übersichtliche Statistiken und Schnellzugriff auf wichtige Funktionen.

### Buchsammlung

Responsive Grid-Layout mit Cover-Vorschau und Status-Badges.

### Buch-Details

Umfassende Ansicht mit allen Metadaten und Aktionsmöglichkeiten.

## 🛠️ Installation

### Voraussetzungen

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   SQLite/MySQL/PostgreSQL

### Schritt-für-Schritt Setup

1. **Repository klonen**

    ```bash
    git clone https://github.com/[IhrUsername]/bookshare.git
    cd bookshare
    ```

2. **Dependencies installieren**

    ```bash
    composer install
    npm install
    ```

3. **Environment konfigurieren**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Datenbank Setup**

    ```bash
    # Für SQLite (Standard)
    touch database/database.sqlite

    # .env anpassen für andere Datenbanken
    php artisan migrate
    ```

5. **Storage-Link erstellen**

    ```bash
    php artisan storage:link
    ```

6. **Frontend Assets kompilieren**

    ```bash
    npm run dev
    # oder für Produktion:
    npm run build
    ```

7. **Server starten**
    ```bash
    php artisan serve
    ```

Besuchen Sie `http://localhost:8000` und registrieren Sie sich!

## 🗄️ Datenbank-Schema

### Users Tabelle

-   `id`, `name`, `email`, `password`
-   `avatar` - Pfad zum Profilbild
-   `email_verified_at`, `remember_token`

### Books Tabelle

-   `id`, `title`, `author`, `isbn`
-   `description`, `genre`, `publication_year`
-   `language`, `condition`, `status`
-   `owner_id` (Foreign Key zu users)
-   `image_path` - Pfad zum Buchcover

### Loans Tabelle (vorbereitet für zukünftige Features)

-   Ausleihe-System zwischen Benutzern

## 🔗 API Routes

```php
// Authentifizierte Routen
GET    /books          # Buchsammlung anzeigen
GET    /books/create   # Neues Buch Formular
POST   /books          # Buch speichern
GET    /books/{id}     # Buchdetails
GET    /books/{id}/edit # Buch bearbeiten
PATCH  /books/{id}     # Änderungen speichern
DELETE /books/{id}     # Buch löschen
```

## 🎯 Verwendete Technologien

### Backend

-   **Laravel 11** - PHP Framework
-   **Laravel Breeze** - Authentifizierung
-   **Eloquent ORM** - Datenbankabstraktion
-   **Laravel Validation** - Eingabevalidierung

### Frontend

-   **Blade Templates** - Server-side Rendering
-   **Tailwind CSS** - Utility-first CSS Framework
-   **Alpine.js** - Leichtgewichtige JavaScript-Reaktivität
-   **Heroicons** - Beautiful SVG Icons

### Development

-   **Vite** - Frontend Build Tool
-   **PostCSS** - CSS Processing
-   **Laravel Mix** - Asset Compilation

## 🔒 Sicherheit

-   **CSRF-Protection** - Alle Formulare geschützt
-   **Mass Assignment Protection** - Fillable-Felder definiert
-   **File Upload Validation** - Sichere Bild-Uploads
-   **Route Model Binding** - Automatische Model-Resolving
-   **Authorization Gates** - Benutzer-spezifische Berechtigung

## 📱 Mobile Optimierung

-   **Responsive Breakpoints** - Mobile, Tablet, Desktop
-   **Touch-optimierte Buttons** - Große Touch-Targets
-   **Progressive Enhancement** - Funktioniert ohne JavaScript
-   **Fast Loading** - Optimierte Assets und Queries

## 🚀 Deployment

### Heroku

```bash
# Heroku CLI installieren, dann:
heroku create bookshare-app
git push heroku main
heroku run php artisan migrate
```

### Shared Hosting

1. Code in public_html hochladen
2. `.env` konfigurieren
3. `composer install --optimize-autoloader --no-dev`
4. `php artisan migrate`
5. `php artisan config:cache`

## 🔄 Roadmap

### Geplante Features

-   [ ] **Ausleihe-System** - Bücher zwischen Benutzern verleihen
-   [ ] **Suchfunktion** - Volltextsuche und Filter
-   [ ] **Bewertungen** - 5-Sterne-Bewertungssystem
-   [ ] **Wunschliste** - Gewünschte Bücher markieren
-   [ ] **Community-Features** - Öffentliche Buchkataloge
-   [ ] **API-Integration** - ISBN-Lookup für automatische Daten
-   [ ] **Barcode-Scanner** - Mobile ISBN-Erfassung
-   [ ] **Export/Import** - CSV/Excel-Funktionen

### Technische Verbesserungen

-   [ ] **Caching** - Redis/Memcached für Performance
-   [ ] **Pagination** - Für große Buchsammlungen
-   [ ] **Image Optimization** - Automatische Bildkompression
-   [ ] **PWA** - Progressive Web App Features
-   [ ] **Real-time Updates** - WebSocket-Integration

## 🤝 Contributing

1. Fork das Repository
2. Feature Branch erstellen (`git checkout -b feature/AmazingFeature`)
3. Changes committen (`git commit -m 'Add some AmazingFeature'`)
4. Branch pushen (`git push origin feature/AmazingFeature`)
5. Pull Request öffnen

## 📄 Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Siehe `LICENSE` Datei für Details.

## 👥 Credits

-   **Laravel Framework** - [laravel.com](https://laravel.com)
-   **Tailwind CSS** - [tailwindcss.com](https://tailwindcss.com)
-   **Alpine.js** - [alpinejs.dev](https://alpinejs.dev)
-   **Heroicons** - [heroicons.com](https://heroicons.com)

## 📧 Kontakt

Bei Fragen oder Anregungen können Sie gerne ein Issue erstellen oder sich direkt melden.

---

⭐ **Gefällt Ihnen das Projekt? Geben Sie uns einen Stern!** ⭐
