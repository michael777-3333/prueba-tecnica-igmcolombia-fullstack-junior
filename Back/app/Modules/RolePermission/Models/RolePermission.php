<?php

namespace App\Modules\RolePermission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add your fillable fields here
    ];

    protected $casts = [
        // Add your casts here
        'role_id' => 'integer',
        'permission_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
}
