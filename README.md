# RHU Lucban — eHealth Management System

A comprehensive health information and appointment management system built for the **Rural Health Unit of Lucban, Quezon**, Philippines.

## Tech Stack

| Layer        | Technology                                        |
|-------------|--------------------------------------------------|
| Framework   | [Laravel 11](https://laravel.com/) (PHP 8.2+)   |
| Frontend    | Blade templates, Livewire, Alpine.js             |
| Database    | MySQL 8                                          |
| SMS         | Semaphore API, PhilSMS API                       |
| Email       | Gmail SMTP (via Laravel Mail)                    |
| PDF         | DomPDF + PhpWord                                 |
| Excel       | Maatwebsite/Laravel-Excel                        |
| Maps        | Google Maps JavaScript API                       |
| Dev Server  | Laravel Herd                                     |

## Features

- **Multi-department dashboard** — Blood, Consultation, Laboratory, Vaccination, and Inventory departments each have dedicated dashboards with role-based access.
- **Appointment management** — Patients can book appointments; staff can approve, reject, and track them.
- **Patient records** — Full CRUD with department-specific data (blood type/volume, lab results, vaccination dose tracking, consultation analysis).
- **Blood bank management** — Blood type inventory tracking, turned-over records, and stock deduction.
- **Inventory management** — Medicines, medical supplies, equipment, and vaccines with batch tracking and dispensing.
- **SMS & Email notifications** — Automated appointment confirmations, approval notices, and OTP-based password reset.
- **PDF receipt generation** — Word template → PDF conversion with automatic email attachment.
- **Excel exports** — One-click data export per department.
- **Announcement system** — Super admin can create, edit, and toggle public announcements with images.
- **Activity logging** — All staff actions are logged with timestamps.
- **Account verification** — IT department reviews and approves new patient registrations.

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 8
- Node.js 18+ (for Vite asset compilation)

### Installation

```bash
# Clone the repository
git clone <repo-url> rhu && cd rhu

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Create an empty MySQL database named 'rhu' and run migrations
php artisan migrate

# Create the initial IT administrator (prompts for credentials)
php artisan rhu:create-admin

# Create the storage symlink
php artisan storage:link

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Environment Variables

Copy `.env.example` and fill in the required values:

| Variable           | Description                          |
|-------------------|--------------------------------------|
| `MAIL_USERNAME`    | Gmail address for sending emails     |
| `MAIL_PASSWORD`    | Gmail app password                   |
| `SEMAPHORE_API_KEY`| Semaphore SMS API key               |
| `PHILSMS_TOKEN`    | PhilSMS bearer token                |
| `GOOGLE_MAPS_KEY`  | Google Maps JavaScript API key      |

## Project Structure

```
app/
├── Enums/          # Department, UserStatus, AppointmentStatus
├── Exports/        # Excel export logic
├── Http/
│   └── Controllers/
│       ├── BloodController.php
│       ├── ConsultationController.php
│       ├── LaboratoryController.php
│       ├── VaccinationController.php
│       ├── InventoryController.php
│       ├── SuperAdminController.php
│       ├── ITDepartmentController.php
│       ├── ClientController.php
│       ├── SessionController.php
│       └── ...
├── Mail/           # Mailable classes
├── Models/         # Eloquent models with relationships
├── Services/       # Appointments, stock transactions, receipts, and SMS
└── Traits/         # LogsActivity trait
```

The schema migration is for fresh installations. For an existing SQL-imported database, baseline its schema before applying the new initial migration; do not run `migrate:fresh` against existing clinical data.

## Verification

```bash
php artisan test
php vendor/bin/pint --test
npm run build
```

Tests use SQLite in memory, bypass cached application configuration, and mock outbound email/SMS. The receipt template is included at `resources/templates/official-receipt.docx`.

## License

This project is proprietary software developed for the Rural Health Unit of Lucban.
