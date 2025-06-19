# BookShare - Laravel Book Sharing Platform

A comprehensive book sharing platform built with Laravel, featuring user authentication, book management, and loan tracking.

## Features

-   **User Authentication**: Complete registration, login, and profile management
-   **Book Management**: Add, edit, view, and manage books
-   **Loan System**: Track book loans and returns
-   **User Profiles**: Customizable user profiles with avatar support
-   **Responsive Design**: Modern UI built with Tailwind CSS and Laravel Blade

## Project Structure

### Models

-   `User.php` - User authentication and profile management
-   `Book.php` - Book information and management
-   `Loan.php` - Book loan tracking system

### Controllers

-   `BookController.php` - Handles all book-related operations
-   `ProfileController.php` - User profile management
-   Authentication controllers in `Auth/` directory

### Views

-   `books/` - Book management interface
-   `auth/` - Authentication pages
-   `profile/` - User profile pages
-   `layouts/` - Application layouts
-   `components/` - Reusable UI components

## Database

The application includes migrations for:

-   Users table with avatar support
-   Books table for book information
-   Loans table for tracking book loans

## Installation

1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Build assets: `npm run dev`
9. Start server: `php artisan serve`

## Technologies Used

-   **Backend**: Laravel 11
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Database**: MySQL/SQLite
-   **Authentication**: Laravel Breeze
-   **Build Tools**: Vite, NPM

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
