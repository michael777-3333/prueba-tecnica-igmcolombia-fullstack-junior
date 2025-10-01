<?php

namespace App\Modules\AttachedFile\Services;

use App\Modules\AttachedFile\Models\AttachedFile;
use Illuminate\Database\Eloquent\Collection;

class AttachedFileService
{
    public function getAllAttachedFiles(): Collection
    {
        return AttachedFile::all();
    }

    public function getAttachedFileById(int $id): ?AttachedFile
    {
        return AttachedFile::find($id);
    }

    public function createAttachedFile(array $data): AttachedFile
    {
        return AttachedFile::create($data);
    }

    public function updateAttachedFile(AttachedFile $AttachedFile, array $data): AttachedFile
    {
        $AttachedFile->update($data);
        return $AttachedFile->fresh();
    }

    public function deleteAttachedFile(AttachedFile $AttachedFile): bool
    {
        return $AttachedFile->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveAttachedFiles(): Collection
    // {
    //     return AttachedFile::where('is_active', true)->get();
    // }
}