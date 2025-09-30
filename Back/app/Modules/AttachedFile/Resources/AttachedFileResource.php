<?php

namespace App\Modules\AttachedFile\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachedFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'upload_date' => $this->upload_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}