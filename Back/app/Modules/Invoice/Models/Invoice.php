<?php

namespace App\Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice_number',
        'description',
        'notes',
        'issue_date',
        'due_date',
        'total_amount',
        'status',
        'customer_id',
        'user_id'
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}