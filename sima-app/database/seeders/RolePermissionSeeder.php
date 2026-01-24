<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create Permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Assets
            'view-assets',
            'create-assets',
            'edit-assets',
            'delete-assets',
            'generate-qr',
            
            // Categories
            'view-categories',
            'manage-categories',
            
            // Locations
            'view-locations',
            'manage-locations',
            
            // Vendors
            'view-vendors',
            'manage-vendors',
            
            // Movements
            'view-movements',
            'create-movements',
            'approve-movements',
            
            // Maintenance
            'view-maintenances',
            'create-maintenances',
            'manage-maintenances',
            
            // Stock Opname
            'view-stock-opnames',
            'create-stock-opnames',
            'scan-stock-opnames',
            
            // Reports
            'view-reports',
            'export-reports',
            
            // Users
            'manage-users',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Create Roles and assign permissions
        
        // 1. Admin Aset - Full Access
        $adminRole = Role::create(['name' => 'Admin Aset']);
        $adminRole->givePermissionTo(Permission::all());
        
        // 2. Pimpinan/Manajemen - View Only + Reports
        $managerRole = Role::create(['name' => 'Pimpinan']);
        $managerRole->givePermissionTo([
            'view-dashboard',
            'view-assets',
            'view-movements',
            'view-maintenances',
            'view-stock-opnames',
            'view-reports',
            'export-reports',
        ]);
        
        // 3. Staff/Peminjam - Limited Access
        $staffRole = Role::create(['name' => 'Staff']);
        $staffRole->givePermissionTo([
            'view-dashboard',
            'view-assets',
            'create-movements', // Request peminjaman
            'create-maintenances', // Lapor kerusakan
        ]);
        
        // 4. Auditor - Stock Opname Access
        $auditorRole = Role::create(['name' => 'Auditor']);
        $auditorRole->givePermissionTo([
            'view-dashboard',
            'view-assets',
            'view-stock-opnames',
            'create-stock-opnames',
            'scan-stock-opnames',
        ]);
        
        // Create Default Users
        
        // Admin User
        $admin = User::create([
            'name' => 'Admin SIMA',
            'email' => 'admin@sima.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin Aset');
        
        // Manager User
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@sima.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('Pimpinan');
        
        // Staff User
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@sima.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('Staff');
        
        // Auditor User
        $auditor = User::create([
            'name' => 'Auditor',
            'email' => 'auditor@sima.com',
            'password' => Hash::make('password'),
        ]);
        $auditor->assignRole('Auditor');
    }
}
