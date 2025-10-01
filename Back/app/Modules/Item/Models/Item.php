<?php

namespace App\Modules\Item\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'price',
        'quantity',
        'total',
        'iva',
       
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}