# 🌏 BookShare - Persische (Farsi) Übersetzung

## 📋 Übersetzungsplan: Deutsch → فارسی

### 🎯 Ziel

Alle deutschen Texte in der BookShare-Anwendung ins Persische (Farsi) übersetzen.

---

## 📚 ÜBERSETZUNGSLISTE

### 1. 🏠 Navigation & Layout

| Deutsch         | فارسی (Farsi) | Datei                          |
| --------------- | ------------- | ------------------------------ |
| Dashboard       | داشبورد       | `layouts/navigation.blade.php` |
| Bücher          | کتاب‌ها       | `layouts/navigation.blade.php` |
| Ausleihvorgänge | امانت‌ها      | `layouts/navigation.blade.php` |
| Profil          | پروفایل       | `layouts/navigation.blade.php` |
| Abmelden        | خروج          | `layouts/navigation.blade.php` |
| Anmelden        | ورود          | `auth/login.blade.php`         |
| Registrieren    | ثبت‌نام       | `auth/register.blade.php`      |

### 2. 📖 Buchverwaltung

| Deutsch          | فارسی (Farsi) | Kontext  |
| ---------------- | ------------- | -------- |
| Buch hinzufügen  | افزودن کتاب   | Button   |
| Titel            | عنوان         | Formfeld |
| Autor            | نویسنده       | Formfeld |
| Beschreibung     | توضیحات       | Formfeld |
| Genre            | ژانر          | Formfeld |
| Erscheinungsjahr | سال انتشار    | Formfeld |
| Sprache          | زبان          | Formfeld |
| Zustand          | وضعیت         | Formfeld |
| Cover            | جلد           | Formfeld |
| Sehr gut         | عالی          | Zustand  |
| Gut              | خوب           | Zustand  |
| Befriedigend     | قابل قبول     | Zustand  |
| Akzeptabel       | مناسب         | Zustand  |

### 3. 📊 Status-Übersetzungen

| Deutsch       | فارسی (Farsi)  | Kontext         |
| ------------- | -------------- | --------------- |
| Verfügbar     | در دسترس       | Buchstatus      |
| Ausgeliehen   | امانت داده شده | Buchstatus      |
| Reserviert    | رزرو شده       | Buchstatus      |
| Angefragt     | درخواست شده    | Buchstatus      |
| Aktiv         | فعال           | Ausleihe-Status |
| Zurückgegeben | برگردانده شده  | Ausleihe-Status |
| Abgelehnt     | رد شده         | Ausleihe-Status |

### 4. 🔧 Aktionen & Buttons

| Deutsch              | فارسی (Farsi)   | Kontext |
| -------------------- | --------------- | ------- |
| Details ansehen      | مشاهده جزئیات   | Button  |
| Bearbeiten           | ویرایش          | Button  |
| Löschen              | حذف             | Button  |
| Ausleihen anfragen   | درخواست امانت   | Button  |
| Zurück zur Übersicht | بازگشت به فهرست | Button  |
| Speichern            | ذخیره           | Button  |
| Abbrechen            | لغو             | Button  |

### 5. 💬 Nachrichten & Benachrichtigungen

| Deutsch                                      | فارسی (Farsi)                            | Kontext |
| -------------------------------------------- | ---------------------------------------- | ------- |
| Buch wurde erfolgreich hinzugefügt!          | کتاب با موفقیت اضافه شد!                 | Erfolg  |
| Buch wurde erfolgreich aktualisiert!         | کتاب با موفقیت به‌روزرسانی شد!           | Erfolg  |
| Buch wurde erfolgreich gelöscht!             | کتاب با موفقیت حذف شد!                   | Erfolg  |
| Ausleihantrag wurde erfolgreich gesendet!    | درخواست امانت با موفقیت ارسال شد!        | Erfolg  |
| Dieses Buch ist nicht verfügbar.             | این کتاب در دسترس نیست.                  | Fehler  |
| Sie können Ihr eigenes Buch nicht ausleihen. | شما نمی‌توانید کتاب خود را امانت بگیرید. | Fehler  |

### 6. 🔐 Fehler- & Berechtigungsmeldungen

| Deutsch                                       | فارسی (Farsi)                                 | Kontext      |
| --------------------------------------------- | --------------------------------------------- | ------------ |
| Sie haben keine Berechtigung                  | شما مجوز ندارید                               | Fehler 403   |
| Das Buch ist derzeit nicht verfügbar          | کتاب در حال حاضر در دسترس نیست                | Debug        |
| Sie können nur Ihre eigenen Bücher bearbeiten | شما فقط می‌توانید کتاب‌های خود را ویرایش کنید | Berechtigung |
| Sie können nur Ihre eigenen Bücher löschen    | شما فقط می‌توانید کتاب‌های خود را حذف کنید    | Berechtigung |

---

## 🛠️ IMPLEMENTIERUNGSPLAN

### Phase 1: Laravel Localization Setup

1. **Sprachkonfiguration** erstellen
2. **Farsi Sprachdateien** hinzufügen
3. **Locale Middleware** implementieren

### Phase 2: View-Dateien übersetzen

1. **Navigation** übersetzen
2. **Book Views** übersetzen
3. **Loan Views** übersetzen
4. **Auth Views** übersetzen

### Phase 3: Controller Messages

1. **Flash Messages** übersetzen
2. **Validation Messages** übersetzen
3. **Error Messages** übersetzen

### Phase 4: RTL Support (Optional)

1. **CSS für RTL** anpassen
2. **Layout-Anpassungen** für Persisch

---

## 📁 DATEIEN ZU BEARBEITEN

### Sprachdateien (Neu erstellen):

-   `resources/lang/fa/auth.php`
-   `resources/lang/fa/books.php`
-   `resources/lang/fa/loans.php`
-   `resources/lang/fa/messages.php`

### Views zu übersetzen:

-   `resources/views/layouts/navigation.blade.php`
-   `resources/views/books/*.blade.php`
-   `resources/views/loans/*.blade.php`
-   `resources/views/auth/*.blade.php`
-   `resources/views/dashboard.blade.php`

### Controller zu aktualisieren:

-   `app/Http/Controllers/BookController.php`
-   `app/Http/Controllers/LoanController.php`
-   `app/Http/Controllers/Auth/*.php`

---

## 🌐 BEISPIEL-IMPLEMENTIERUNG

### Sprachdatei (`resources/lang/fa/books.php`):

```php
<?php

return [
    'title' => 'عنوان',
    'author' => 'نویسنده',
    'description' => 'توضیحات',
    'genre' => 'ژانر',
    'add_book' => 'افزودن کتاب',
    'edit_book' => 'ویرایش کتاب',
    'view_details' => 'مشاهده جزئیات',
    'borrow_request' => 'درخواست امانت',
    'book_added' => 'کتاب با موفقیت اضافه شد!',
    'book_updated' => 'کتاب با موفقیت به‌روزرسانی شد!',
    'book_deleted' => 'کتاب با موفقیت حذف شد!',
    'status' => [
        'available' => 'در دسترس',
        'borrowed' => 'امانت داده شده',
        'reserved' => 'رزرو شده',
        'requested' => 'درخواست شده',
    ],
    'condition' => [
        'sehr_gut' => 'عالی',
        'gut' => 'خوب',
        'befriedigend' => 'قابل قبول',
        'akzeptabel' => 'مناسب',
    ],
];
```

### View-Verwendung:

```blade
{{-- Statt: --}}
<h1>Bücher</h1>
<button>Buch hinzufügen</button>

{{-- Wird zu: --}}
<h1>{{ __('books.books') }}</h1>
<button>{{ __('books.add_book') }}</button>
```

---

## ✅ BEREIT FÜR IMPLEMENTATION?

Soll ich jetzt beginnen mit:

1. **Laravel Localization Setup**
2. **Erste Sprachdateien erstellen**
3. **Navigation übersetzen**

**Welchen Teil möchten Sie zuerst umgesetzt haben?**
