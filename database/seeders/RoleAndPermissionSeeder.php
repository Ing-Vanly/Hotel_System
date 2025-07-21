<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Booking Management
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            
            // Customer Management
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            
            // Room Management
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            
            // Employee Management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Leave Management
            'view leaves',
            'create leaves',
            'edit leaves',
            'delete leaves',
            'approve leaves',
            
            // Front Desk Operations
            'checkin guests',
            'checkout guests',
            'view arrivals',
            'view departures',
            'room assignment',
            
            // Housekeeping Operations
            'view room status',
            'update room status',
            'cleaning schedule',
            'maintenance requests',
            
            // Reports
            'view reports',
            'generate reports',
            
            // System Settings
            'system settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Hotel Manager - most permissions except system settings
        $manager = Role::firstOrCreate(['name' => 'Hotel Manager']);
        $managerPermissions = [
            'view users', 'create users', 'edit users',
            'view roles',
            'view bookings', 'create bookings', 'edit bookings', 'delete bookings',
            'view customers', 'create customers', 'edit customers', 'delete customers',
            'view rooms', 'create rooms', 'edit rooms', 'delete rooms',
            'view employees', 'create employees', 'edit employees',
            'view leaves', 'edit leaves', 'approve leaves',
            'checkin guests', 'checkout guests', 'view arrivals', 'view departures', 'room assignment',
            'view room status', 'update room status', 'cleaning schedule', 'maintenance requests',
            'view reports', 'generate reports',
        ];
        $manager->syncPermissions($managerPermissions);

        // Front Desk Staff
        $frontDesk = Role::firstOrCreate(['name' => 'Front Desk Staff']);
        $frontDeskPermissions = [
            'view bookings', 'create bookings', 'edit bookings',
            'view customers', 'create customers', 'edit customers',
            'view rooms',
            'checkin guests', 'checkout guests', 'view arrivals', 'view departures', 'room assignment',
        ];
        $frontDesk->syncPermissions($frontDeskPermissions);

        // Housekeeping Staff
        $housekeeping = Role::firstOrCreate(['name' => 'Housekeeping Staff']);
        $housekeepingPermissions = [
            'view room status', 'update room status', 'cleaning schedule', 'maintenance requests',
            'view rooms',
        ];
        $housekeeping->syncPermissions($housekeepingPermissions);

        // Reception
        $reception = Role::firstOrCreate(['name' => 'Reception']);
        $receptionPermissions = [
            'view bookings', 'create bookings', 'edit bookings',
            'view customers', 'create customers', 'edit customers',
            'view rooms',
            'checkin guests', 'checkout guests', 'view arrivals', 'view departures',
        ];
        $reception->syncPermissions($receptionPermissions);

        // Employee (General)
        $employee = Role::firstOrCreate(['name' => 'Employee']);
        $employeePermissions = [
            'view leaves', 'create leaves',
            'view bookings',
            'view customers',
            'view rooms',
        ];
        $employee->syncPermissions($employeePermissions);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}