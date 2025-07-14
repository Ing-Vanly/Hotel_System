# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a hotel management system built with Laravel 10, featuring customer management, room booking, employee management, leave management, and role-based permissions. The system uses Spatie Laravel Permission for role management and includes a comprehensive admin interface.

## Core Architecture

**Backend Framework**: Laravel 10 with PHP 8.1+
**Frontend**: Blade templates with Bootstrap, AlpineJS, and Tailwind CSS
**Database**: MySQL with comprehensive migrations
**Authentication**: Laravel Breeze with custom multi-auth system
**Permissions**: Spatie Laravel Permission package for role-based access control

### Key Models and Relationships
- `User` - System users with roles and permissions
- `Employee` - Hotel staff with personal details, positions, and salaries
- `Customer` - Hotel guests with auto-generated customer IDs
- `Booking` - Room reservations linked to customers
- `Room` - Hotel rooms with types and availability
- `Leave` / `LeaveType` - Employee leave management system
- `Role` - User roles with permissions using Spatie package

### Core Modules
1. **User Management** - Admin users, employees, role assignments
2. **Customer Management** - Guest registration with auto-generated IDs
3. **Room Management** - Room types, availability, and booking
4. **Booking System** - Reservation management with file uploads
5. **Employee Management** - Staff records, positions, salaries
6. **Leave Management** - Leave types and leave applications
7. **Role & Permissions** - Fine-grained access control

## Development Commands

### Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Development
```bash
php artisan serve          # Start development server
npm run dev               # Start Vite development server
npm run build            # Build assets for production
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh migration with seeders
php artisan db:seed             # Run seeders only
```

### Testing
```bash
php artisan test          # Run PHPUnit tests
./vendor/bin/pest         # Run Pest tests (alternative test runner)
```

### Code Quality
```bash
./vendor/bin/pint         # Laravel Pint code formatting
php artisan route:list    # List all routes
php artisan optimize      # Optimize for production
```

## Key Features

### Auto-Generated IDs
The system includes custom ID generation for:
- Customer IDs (auto-generated with sequences)
- Booking IDs (auto-generated with sequences)
- Room IDs (auto-generated with sequences)

### File Upload System
- Employee photos stored in `public/upload/`
- Booking-related file uploads supported
- Image handling with validation

### Role-Based Access Control
Uses Spatie Laravel Permission with:
- Dynamic role assignment
- Permission-based route protection
- Middleware-based access control

### Custom Blade Components
- Application layout with sidebar navigation
- Guest layout for authentication
- Custom form components and inputs
- Modal components for interactions

## Database Structure

### Key Tables
- `users` - System authentication
- `employees` - Staff management
- `customers` - Guest records
- `bookings` - Reservation data
- `rooms` - Room inventory
- `leaves` / `leave_types` - Leave management
- `roles` / `permissions` - Access control
- Sequence tables for auto-generated IDs

### Migrations Location
All migrations are in `database/migrations/` with chronological naming for proper execution order.

## Frontend Assets

### CSS Framework
- **Primary**: Tailwind CSS for utilities
- **Bootstrap**: Legacy components and layout
- **Custom**: `public/assets/css/style.css` for theme styling

### JavaScript
- **Alpine.js** for reactive components
- **jQuery** for legacy functionality
- **Custom scripts** in `public/assets/js/script.js`

### Asset Build
Uses Vite for modern asset compilation with hot reloading support.

## Important Conventions

### Route Structure
- Form routes use `form/` prefix (e.g., `form/allbooking`, `form/addcustomer/page`)
- RESTful resource routes for role management
- Middleware groups for authentication protection

### Controller Organization
Controllers are feature-organized:
- `BookingController` - Reservation management
- `CustomerController` - Guest management  
- `EmployeeController` - Staff management
- `UserManagementController` - System users
- `RoleController` - Permission management

### Model Conventions
- Mass assignment protection with `$fillable` arrays
- Standard Laravel naming conventions
- Relationships defined where needed

## Security Considerations

### Authentication
- Laravel Breeze for base authentication
- Custom login/logout controllers
- Password reset functionality
- Email verification support

### Authorization
- Spatie Laravel Permission for granular control
- Middleware protection on all admin routes
- Role-based access to different modules

### File Security
- Upload validation and sanitization
- Proper file storage location
- Access control for uploaded files

## Development Notes

- The system uses a mix of traditional Laravel patterns and modern practices
- Leave management is a newer feature with full CRUD operations
- Employee management includes photo upload capabilities
- Customer and booking systems have custom ID generation logic
- The codebase includes both PHPUnit and Pest testing frameworks