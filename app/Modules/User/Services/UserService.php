<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(User $User, array $data): User
    {
        $User->update($data);
        return $User->fresh();
    }

    public function deleteUser(User $User): bool
    {
        return $User->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveUsers(): Collection
    // {
    //     return User::where('is_active', true)->get();
    // }
}