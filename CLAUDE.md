# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 10 hotel management system with comprehensive functionality for managing bookings, customers, rooms, employees, and role-based access control. The system includes front desk operations, housekeeping management, employee leave tracking, and customer relationship management.

## Technology Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade templates with Bootstrap, jQuery, Alpine.js
- **Build Tool**: Vite with Laravel Vite Plugin
- **Database**: MySQL (configured in .env as `hotel_db`)
- **Authentication**: Laravel Sanctum + Laravel UI
- **Permissions**: Spatie Laravel Permission package
- **Notifications**: Laravel Toastr (brian2694/laravel-toastr)

## Development Commands

### Essential Laravel Commands
```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Run development server
php artisan serve

# Build assets
npm run dev          # Development with hot reload
npm run build        # Production build

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

### Testing Commands
```bash
# Run PHPUnit tests
php artisan test

# Run Pest tests (configured)
./vendor/bin/pest

# Run specific test
php artisan test --filter=ExampleTest
```

### Code Quality Commands
```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# Debug with Laravel Debugbar (development only)
# Accessible via web interface when APP_DEBUG=true
```

## Application Architecture

### Core Business Modules
1. **User Management**: Authentication, roles, permissions using Spatie Permission package
2. **Hotel Operations**: Room management, booking system, customer management
3. **Employee Management**: Staff records, leave management, role assignments
4. **Front Desk**: Check-in/out operations, booking modifications
5. **Housekeeping**: Room status management, maintenance tracking

### Data Model Architecture
- **Users**: Authentication with dual role system (direct role_id + Spatie permissions)
- **Bookings**: Complete reservation lifecycle (pending → confirmed → checked_in → checked_out)
- **Rooms**: Status management (available, occupied, maintenance, dirty, out_of_order)
- **Customers**: Guest information with auto-generated customer IDs
- **Employees**: Staff management with leave tracking integration
- **Role/Permission System**: Hybrid approach using both Spatie package and direct role assignment

### Key Model Relationships
- User ↔ Role (belongsTo with dual system)
- Booking ↔ Customer, Room, RoomType (belongsTo relationships)
- Room ↔ RoomType (belongsTo), Room ↔ Booking (hasMany)
- Employee ↔ Leave (hasMany), Leave ↔ LeaveType (belongsTo)

### Special Model Features
- **Auto-Generated IDs**: User model auto-generates "KH_001" format IDs
- **Soft Deletes**: Implemented on User model for data retention
- **Status Workflows**: Comprehensive state management for bookings, rooms, leaves
- **Legacy Compatibility**: Booking model maintains old field structure during migration

## Database Configuration

Default database connection expects:
- **Host**: 127.0.0.1
- **Database**: hotel_db
- **Username**: root
- **Password**: (empty)

Migrations include comprehensive hotel management schema with proper foreign key relationships.

## Key Directories and Files

### Application Structure
- `app/Http/Controllers/`: Business logic controllers for each module
- `app/Models/`: Eloquent models with relationships and business logic
- `resources/views/`: Blade templates organized by functionality
- `database/migrations/`: Database schema with hotel-specific tables
- `database/seeders/`: Role and permission seeding

### Important Configuration Files
- `routes/web.php`: All web routes with middleware protection
- `config/permission.php`: Spatie permission package configuration
- `vite.config.js`: Frontend build configuration
- `.env.example`: Environment configuration template

### Asset Management
- CSS/JS assets in `public/assets/`
- File uploads in `public/assets/upload/`
- Vite builds from `resources/css/app.css` and `resources/js/app.js`

## Development Notes

### Authentication & Authorization
- Uses Laravel's built-in auth with custom registration
- Implements Spatie Permission package for advanced role/permission management
- Role-based access control throughout the application
- Custom middleware for different user types

### Business Logic Patterns
- Controllers handle HTTP concerns and delegate to models
- Models contain business rules and relationship logic
- Blade components for reusable UI elements
- Helper functions for common operations (e.g., set_active for navigation)

### Data Validation
- Form request validation classes in `app/Http/Requests/`
- Model-level validation and business rules
- Frontend validation with Bootstrap styling

### File Upload Management
- Image uploads stored in `public/assets/upload/`
- Profile pictures and document management included

## Testing Setup

Project includes Pest testing framework with Laravel plugin. Test database should be configured separately from development database.

## Deployment Considerations

- Set `APP_ENV=production` and `APP_DEBUG=false` for production
- Run `php artisan optimize` for production optimization
- Configure proper database credentials
- Set up proper file permissions for storage directories
- Consider using queue workers for background jobs if needed