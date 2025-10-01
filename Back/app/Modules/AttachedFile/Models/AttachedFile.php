<?php

namespace App\Modules\AttachedFile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'file_path',
        'file_type',
        'upload_date',
    ];

    protected $casts = [
        // Add your casts here
    ];

    // Add your relationships here
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}