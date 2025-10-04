<?php

namespace App\Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Add your relationships here
    public function users()
    {
        return $this->hasMany(\App\Modules\User\Models\User::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(\App\Modules\Permission\Models\Permission::class,'role_permissions')
                ->withTimestamps();
        ;
    }
}
