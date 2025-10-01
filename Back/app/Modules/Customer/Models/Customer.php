<?php

namespace App\Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'document_type',
        'document',
        'email',
        'phone'
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
