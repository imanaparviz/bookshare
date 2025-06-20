# 📚 ارائه کامل پروژه کتاب‌یار همگانی (BookShare)

## 🎯 از صفر تا صد: توسعه یک سیستم مدیریت کتابخانه مدرن

---

## 📋 فهرست مطالب

1. [معرفی پروژه](#معرفی-پروژه)
2. [تکنولوژی‌های استفاده شده](#تکنولوژی‌های-استفاده-شده)
3. [مراحل توسعه گام‌به‌گام](#مراحل-توسعه-گام‌به‌گام)
4. [دستورات اجرا شده](#دستورات-اجرا-شده)
5. [ساختار پروژه](#ساختار-پروژه)
6. [ویژگی‌های کلیدی](#ویژگی‌های-کلیدی)
7. [بهترین روش‌های اجرا شده](#بهترین-روش‌های-اجرا-شده)
8. [راهنمای استفاده](#راهنمای-استفاده)

---

## 🎯 معرفی پروژه

**نام پروژه:** کتاب‌یار همگانی (BookShare)

**هدف:** ایجاد یک سیستم مدیریت کتابخانه آنلاین که امکان به اشتراک‌گذاری و امانت کتاب‌ها بین کاربران را فراهم می‌کند.

### ویژگی‌های اصلی:

-   ✅ مدیریت کامل کتاب‌ها (CRUD)
-   ✅ سیستم امانت پیشرفته
-   ✅ هوش مصنوعی برای دسته‌بندی خودکار
-   ✅ سیستم پیشنهادات هوشمند
-   ✅ داشبورد مدرن و تعاملی
-   ✅ احراز هویت کامل
-   ✅ رابط کاربری حرفه‌ای

---

## 🛠️ تکنولوژی‌های استفاده شده

### Backend:

-   **Laravel 12.x** - Framework اصلی PHP
-   **PHP 8.2+** - زبان برنامه‌نویسی
-   **SQLite** - پایگاه داده
-   **Eloquent ORM** - مدیریت پایگاه داده

### Frontend:

-   **Laravel Blade** - Template Engine
-   **Tailwind CSS** - Framework CSS
-   **Alpine.js** - JavaScript Framework سبک
-   **Vite** - Build Tool مدرن

### ابزارهای توسعه:

-   **Laravel Breeze** - سیستم احراز هویت
-   **Composer** - مدیریت وابستگی‌های PHP
-   **NPM** - مدیریت وابستگی‌های JavaScript
-   **Git** - کنترل نسخه

---

## 📈 مراحل توسعه گام‌به‌گام

### 🔧 فاز ۱: آماده‌سازی محیط توسعه

#### گام ۱: نصب Laravel

```bash
composer create-project laravel/laravel bookshare
cd bookshare
```

#### گام ۲: تنظیم فایل محیطی

```bash
php artisan key:generate
# تنظیم .env برای اتصال به پایگاه داده
```

#### گام ۳: نصب سیستم احراز هویت

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
php artisan migrate
```

### 🗄️ فاز ۲: طراحی پایگاه داده

#### گام ۱: ایجاد مهاجرت‌ها (Migrations)

```bash
# ایجاد جدول کتاب‌ها
php artisan make:migration create_books_table

# ایجاد جدول امانت‌ها
php artisan make:migration create_loans_table

# اضافه کردن فیلد آواتار به کاربران
php artisan make:migration add_avatar_to_users_table
```

#### گام ۲: طراحی ساختار جداول

**جدول Books:**

-   id: شناسه یکتا
-   title: عنوان کتاب
-   author: نویسنده
-   isbn: شماره شابک
-   description: توضیحات
-   genre: دسته‌بندی
-   publication_year: سال انتشار
-   language: زبان
-   condition: وضعیت فیزیکی
-   status: وضعیت دسترسی
-   owner_id: صاحب کتاب
-   image_path: مسیر تصویر جلد

**جدول Loans:**

-   id: شناسه یکتا
-   book_id: شناسه کتاب
-   borrower_id: شناسه امانت‌گیرنده
-   lender_id: شناسه امانت‌دهنده
-   loan_date: تاریخ امانت
-   due_date: تاریخ بازگشت
-   return_date: تاریخ بازگشت واقعی
-   status: وضعیت امانت
-   notes: یادداشت‌ها

#### گام ۳: اجرای مهاجرت‌ها

```bash
php artisan migrate
```

### 🛠️ فاز ۳: ایجاد مدل‌ها و روابط

#### گام ۱: ایجاد مدل‌ها

```bash
php artisan make:model Book
php artisan make:model Loan
```

#### گام ۲: تعریف روابط در مدل‌ها

**مدل Book:**

-   belongsTo: User (owner)
-   hasMany: Loan

**مدل Loan:**

-   belongsTo: Book
-   belongsTo: User (borrower)
-   belongsTo: User (lender)

**مدل User:**

-   hasMany: Book (owned books)
-   hasMany: Loan (borrowed loans)
-   hasMany: Loan (lent loans)

### 🎨 فاز ۴: توسعه رابط کاربری

#### گام ۱: ایجاد کنترلرها

```bash
php artisan make:controller BookController --resource
php artisan make:controller LoanController
```

#### گام ۲: تعریف مسیرها (Routes)

```php
// در فایل routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class);
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'store']);
    // سایر مسیرها...
});
```

#### گام ۳: ایجاد نماها (Views)

-   `books/index.blade.php` - لیست کتاب‌ها
-   `books/create.blade.php` - فرم ثبت کتاب
-   `books/show.blade.php` - جزئیات کتاب
-   `books/edit.blade.php` - ویرایش کتاب
-   `loans/index.blade.php` - مدیریت امانت‌ها
-   `loans/show.blade.php` - جزئیات امانت

### 🤖 فاز ۵: پیاده‌سازی هوش مصنوعی

#### گام ۱: ایجاد سرویس دسته‌بندی

```bash
# ایجاد کلاس سرویس
touch app/Services/BookCategorizationService.php
```

#### گام ۲: الگوریتم دسته‌بندی خودکار

-   تحلیل کلمات کلیدی
-   امتیازدهی بر اساس محتوا
-   ادغام با API های خارجی (اختیاری)

#### گام ۳: سیستم پیشنهادات

-   پیشنهاد بر اساس دسته‌بندی
-   مرتب‌سازی بر اساس محبوبیت
-   فیلتر کردن کتاب‌های موجود

### 📊 فاز ۶: ایجاد داشبورد

#### گام ۱: آمارگیری

-   تعداد کتاب‌های کاربر
-   امانت‌های فعال
-   درخواست‌های در انتظار
-   کتاب‌های موجود در سیستم

#### گام ۲: اکشن‌های سریع

-   افزودن کتاب جدید
-   مدیریت امانت‌ها
-   جستجوی کتاب‌ها

### 🎨 فاز ۷: بهبود طراحی

#### گام ۱: استایل‌دهی با Tailwind

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

#### گام ۲: طراحی واکنش‌گرا

-   پشتیبانی از موبایل
-   حالت تاریک/روشن
-   انیمیشن‌های روان

---

## 💻 دستورات اجرا شده

### دستورات نصب و راه‌اندازی:

```bash
# ایجاد پروژه
composer create-project laravel/laravel bookshare

# نصب احراز هویت
composer require laravel/breeze --dev
php artisan breeze:install blade

# نصب وابستگی‌ها
npm install && npm run dev

# اجرای مهاجرت‌ها
php artisan migrate
```

### دستورات ایجاد کامپوننت‌ها:

```bash
# مدل‌ها
php artisan make:model Book
php artisan make:model Loan

# کنترلرها
php artisan make:controller BookController --resource
php artisan make:controller LoanController

# مهاجرت‌ها
php artisan make:migration create_books_table
php artisan make:migration create_loans_table
php artisan make:migration add_avatar_to_users_table
```

### دستورات اجرا:

```bash
# اجرای سرور توسعه
php artisan serve

# ساخت asset ها
npm run build

# مود توسعه Vite
npm run dev
```

### دستورات Git:

```bash
# اضافه کردن تغییرات
git add .

# کامیت تغییرات
git commit -m "feature: complete book management system"

# پوش به repository
git push origin main
```

---

## 🏗️ ساختار پروژه

```
bookshare/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── BookController.php
│   │       └── LoanController.php
│   │
│   ├── Models/
│   │   ├── Book.php
│   │   ├── Loan.php
│   │   └── User.php
│   │
│   └── Services/
│       └── BookCategorizationService.php
├── database/
│   ├── migrations/
│   │   ├── create_books_table.php
│   │   ├── create_loans_table.php
│   │   └── add_avatar_to_users_table.php
│   │
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── books/
│   │   ├── loans/
│   │   └── layouts/
│   │
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php
│   └── auth.php
└── public/
    └── storage/ (symlink)
```

---

## ⭐ ویژگی‌های کلیدی

### 1. مدیریت کتاب‌ها

-   **ثبت کتاب:** افزودن کتاب جدید با تمام جزئیات
-   **ویرایش:** تغییر اطلاعات کتاب
-   **حذف:** حذف کتاب از مجموعه
-   **آپلود تصویر:** بارگذاری تصویر جلد کتاب

### 2. سیستم امانت

-   **درخواست امانت:** ارسال درخواست برای امانت کتاب
-   **تایید/رد:** تصمیم‌گیری درباره درخواست‌ها
-   **بازگشت:** ثبت بازگشت کتاب
-   **پیگیری:** نمایش وضعیت همه امانت‌ها

### 3. هوش مصنوعی

-   **دسته‌بندی خودکار:** تشخیص خودکار نوع کتاب
-   **پیشنهاد هوشمند:** پیشنهاد کتاب‌های مشابه
-   **امتیازدهی:** رتبه‌بندی بر اساس محبوبیت

### 4. رابط کاربری

-   **طراحی واکنش‌گرا:** سازگار با همه دستگاه‌ها
-   **حالت تاریک/روشن:** انتخاب تم
-   **ناوبری آسان:** دسترسی سریع به تمام بخش‌ها

---

## 🎯 بهترین روش‌های اجرا شده

### 1. معماری MVC

-   **مدل:** مدیریت داده‌ها و روابط
-   **نما:** نمایش اطلاعات به کاربر
-   **کنترلر:** منطق کسب‌وکار

### 2. امنیت

-   **احراز هویت:** Laravel Breeze
-   **مجوزدهی:** بررسی مالکیت قبل از اعمال تغییرات
-   **اعتبارسنجی:** بررسی ورودی‌های کاربر
-   **CSRF Protection:** محافظت در برابر حملات

### 3. عملکرد

-   **Eager Loading:** بارگیری بهینه روابط
-   **Query Optimization:** بهینه‌سازی کوئری‌ها
-   **Asset Optimization:** فشرده‌سازی CSS/JS

### 4. قابلیت نگهداری

-   **کد تمیز:** رعایت استانداردهای PSR
-   **مستندسازی:** کامنت‌گذاری مناسب
-   **ساختار منطقی:** تفکیک مسئولیت‌ها

---

## 📱 راهنمای استفاده

### 1. شروع به کار

1. **ثبت‌نام:** ایجاد حساب کاربری جدید
2. **ورود:** استفاده از ایمیل و رمز عبور

### 2. مدیریت کتاب‌ها

1. **افزودن کتاب:**

    - کلیک روی "مقل Buch hinzufügen"
    - پر کردن فرم
    - آپلود تصویر جلد (اختیاری)
    - ذخیره

2. **مشاهده کتاب‌ها:**
    - رفتن به بخش "Meine Bücher"
    - کلیک روی هر کتاب برای مشاهده جزئیات

### 3. سیستم امانت

1. **درخواست امانت:**

    - مشاهده کتاب مورد نظر
    - کلیک "Ausleihen anfragen"

2. **مدیریت امانت‌ها:**
    - رفتن به "Ausleihen"
    - تایید یا رد درخواست‌ها
    - ثبت بازگشت کتاب

### 4. داشبورد

-   **آمار کلی:** نمایش تعداد کتاب‌ها و امانت‌ها
-   **اکشن‌های سریع:** دسترسی به عملکردهای اصلی
-   **فعالیت‌های اخیر:** نمایش آخرین تراکنش‌ها

---

## 🔧 نکات فنی

### 1. دیتابیس

-   **SQLite:** برای سادگی توسعه
-   **Eloquent ORM:** مدیریت روابط
-   **Migrations:** کنترل نسخه دیتابیس

### 2. Frontend

-   **Blade Components:** قابلیت استفاده مجدد
-   **Tailwind Utilities:** استایل‌دهی سریع
-   **Alpine.js:** تعامل سبک

### 3. Backend

-   **Service Layer:** جداسازی منطق کسب‌وکار
-   **Form Requests:** اعتبارسنجی مرکزی
-   **Resource Controllers:** عملیات CRUD استاندارد

---

## 🚀 مراحل بعدی

### 1. بهبودهای فنی

-   **API REST:** ارائه سرویس‌های وب
-   **Test Coverage:** افزایش پوشش تست
-   **Caching:** بهبود عملکرد
-   **Queue System:** پردازش ناهمزمان

### 2. ویژگی‌های جدید

-   **نظرات و امتیازدهی:** بازخورد کاربران
-   **پیام‌رسانی:** ارتباط مستقیم
-   **گزارش‌گیری:** آمار تفصیلی
-   **اپلیکیشن موبایل:** نسخه نیتیو

### 3. استقرار

-   **CI/CD Pipeline:** استقرار خودکار
-   **Container:** Docker برای استقرار
-   **Monitoring:** نظارت بر عملکرد
-   **Backup:** پشتیبان‌گیری منظم

---

## 📞 تماس و پشتیبانی

برای سوالات فنی یا درخواست ویژگی جدید، لطفاً از طریق repository GitHub تماس بگیرید.

---

**نسخه:** 1.0.0  
**تاریخ آخرین بروزرسانی:** ۱۹ ژوئن ۲۰۲۵  
**توسعه‌دهنده:** تیم BookShare

---

_این پروژه با ❤️ و Laravel ساخته شده است_
