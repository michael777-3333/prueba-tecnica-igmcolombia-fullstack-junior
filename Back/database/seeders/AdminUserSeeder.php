<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Models\User;
use App\Modules\Role\Models\Role;
use App\Modules\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos de administrador
        $adminPermissions = [
            [
                'name' => 'usuarios',
                'description' => 'Permiso para gestionar usuarios',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'roles',
                'description' => 'Permiso para gestionar roles',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'dashboard',
                'description' => 'Permiso para ver el dashboard administrativo',
                'action' => 'view'
            ],
            [
                'name' => 'permisos',
                'description' => 'Permiso para gestionar permisos',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'items',
                'description' => 'Permiso para gestionar items',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'clientes',
                'description' => 'Permiso para gestionar clientes',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'facturas',
                'description' => 'Permiso para gestionar facturas',
                'action' => 'view, create, update, delete'
            ],
            [
                'name' => 'reportes',
                'description' => 'Permiso para ver reportes',
                'action' => 'view'
            ],
            [
                'name' => 'configuracion',
                'description' => 'Permiso para gestionar configuracion',
                'action' => 'view, create, update, delete'
            ],
        ];

        foreach ($adminPermissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                [
                    'description' => $permissionData['description'],
                    'action' => $permissionData['action'],
                    'is_active' => true,
                ]
            );
        }

        // Crear rol de administrador
        $adminRole = Role::firstOrCreate(
            ['name' => 'Administrador'],
            [
                'description' => 'Administrador con acceso completo al sistema',
                'is_active' => true,
            ]
        );

        // Asignar TODOS los permisos al rol admin
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Crear rol de usuario normal si no existe
        $userRole = Role::firstOrCreate(
            ['name' => 'Usuario'],
            [
                'description' => 'Usuario normal del sistema',
                'is_active' => true,
            ]
        );

        // Asignar algunos permisos básicos al rol de usuario
        $basicPermissions = Permission::whereIn('name', ['Ver dashboard'])->get();
        $userRole->permissions()->sync($basicPermissions->pluck('id'));

        // Crear usuario administrador
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario normal
        $normalUser = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Usuario de Prueba',
                'password' => Hash::make('user123'),
                'role_id' => $userRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Seeder ejecutado exitosamente!');
        $this->command->info('👤 Usuario Admin: admin@system.com / admin123');
        $this->command->info('👤 Usuario Normal: user@test.com / user123');
        $this->command->info('🎯 Rol Admin tiene: ' . $adminRole->permissions->count() . ' permisos');
        $this->command->info('👥 Rol Usuario tiene: ' . $userRole->permissions->count() . ' permisos');
    }
}
