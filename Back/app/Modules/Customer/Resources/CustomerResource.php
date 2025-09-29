<?php

namespace App\Modules\Customer\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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