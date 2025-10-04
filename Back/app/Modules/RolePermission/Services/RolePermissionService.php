<?php

namespace App\Modules\RolePermission\Services;

use App\Modules\RolePermission\Models\RolePermission;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionService
{
    public function getAllRolePermissions(): Collection
    {
        return RolePermission::all();
    }

    public function getRolePermissionById(int $id): ?RolePermission
    {
        return RolePermission::find($id);
    }

    public function createRolePermission(array $data): RolePermission
    {
        return RolePermission::create($data);
    }

    public function updateRolePermission(RolePermission $RolePermission, array $data): RolePermission
    {
        $RolePermission->update($data);
        return $RolePermission->fresh();
    }

    public function deleteRolePermission(RolePermission $RolePermission): bool
    {
        return $RolePermission->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveRolePermissions(): Collection
    // {
    //     return RolePermission::where('is_active', true)->get();
    // }
}