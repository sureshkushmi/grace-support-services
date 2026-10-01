# Grace Support Services

Grace Support Services is a Laravel-based backend API for a staff and support services management platform.

The system provides authentication, staff management, shift reporting, staff document management, role-based access, and API endpoints for a Vue.js frontend.

## Features

- User authentication
- Role-based access
- Staff management
- Staff profile management
- Staff document management
- Shift report management
- Shift report status tracking
- PDF shift report generation
- RESTful API
- Laravel Sanctum authentication
- Database migrations and seeders

## Tech Stack

- PHP
- Laravel
- Laravel Sanctum
- MySQL
- REST API
- JavaScript
- Laravel Mix
- Blade
- Composer

## API Features

The backend provides API endpoints for:

- Authentication
- User profiles
- Staff management
- Staff documents
- Shift reports

## Project Structure

app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── Providers/

database/
├── migrations/
├── factories/
└── seeders/

resources/
├── views/
└── lang/

routes/
├── api.php
├── web.php
└── channels.php