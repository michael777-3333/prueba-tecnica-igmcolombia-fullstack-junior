<?php

namespace App\Modules\Role\Services;

use App\Modules\Role\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function getRoleById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function updateRole(Role $Role, array $data): Role
    {
        $Role->update($data);
        return $Role->fresh();
    }

    public function deleteRole(Role $Role): bool
    {
        return $Role->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveRoles(): Collection
    // {
    //     return Role::where('is_active', true)->get();
    // }
}