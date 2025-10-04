<?php

namespace App\Modules\Permission\Services;

use App\Modules\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    public function getPermissionById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function updatePermission(Permission $Permission, array $data): Permission
    {
        $Permission->update($data);
        return $Permission->fresh();
    }

    public function deletePermission(Permission $Permission): bool
    {
        return $Permission->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActivePermissions(): Collection
    // {
    //     return Permission::where('is_active', true)->get();
    // }
}