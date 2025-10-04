<?php

namespace App\Modules\InvoiceItem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'name',
        'quantity',
        'unit_price',
        'tax_rate',
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here
    public function invoice()
    {
        return $this->belongsTo(\App\Modules\Invoice\Models\Invoice::class);
    }

    public function item()
    {
        return $this->belongsTo(\App\Modules\Item\Models\Item::class);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
