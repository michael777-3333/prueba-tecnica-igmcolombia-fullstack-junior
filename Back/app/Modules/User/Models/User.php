<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'position',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function invoices()
    {
        return $this->hasMany(\App\Modules\Invoice\Models\Invoice::class);
    }

    public function customers()
    {
        return $this->hasMany(\App\Modules\Customer\Models\Customer::class);
    }
    public function role()
    {
        return $this->belongsTo(\App\Modules\Role\Models\Role::class);
    }
    public function hasPermission($permissionName)
    {
        // Cargar la relación role.permissions si no está cargada
        if (!$this->relationLoaded('role') || !$this->role->relationLoaded('permissions')) {
            $this->load('role.permissions');
        }

        // Si el usuario no tiene rol, no tiene permisos
        if (!$this->role) {
            return false;
        }

        // Si el rol está inactivo, no tiene permisos
        if (!$this->role->is_active) {
            return false;
        }

        // Verificar si el rol tiene el permiso
        return $this->role->permissions->contains('name', $permissionName);
    }

}
