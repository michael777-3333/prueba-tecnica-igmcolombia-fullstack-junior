<?php

namespace App\Modules\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'action'
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here
    public function roles()
    {
        return $this->belongsToMany(\App\Modules\Role\Models\Role::class,'role_permissions')
                ->withTimestamps();
        ;
    }

}
