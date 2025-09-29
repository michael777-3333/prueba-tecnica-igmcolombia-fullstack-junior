<?php

namespace App\Modules\InvoiceItem\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // Add your resource fields here
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}