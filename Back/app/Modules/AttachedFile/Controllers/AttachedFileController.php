<?php

namespace App\Modules\AttachedFile\Controllers;

use App\Modules\AttachedFile\Models\AttachedFile;
use App\Modules\AttachedFile\Services\AttachedFileService;
use App\Modules\AttachedFile\Resources\AttachedFileResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttachedFileController extends Controller
{
    protected $AttachedFileService;

    public function __construct(AttachedFileService $AttachedFileService)
    {
        $this->AttachedFileService = $AttachedFileService;
    }

    public function index()
    {
        $AttachedFiles = $this->AttachedFileService->getAllAttachedFiles();
        return response()->json([
            'success' => true,
            'data' => $AttachedFiles,
            'count' => $AttachedFiles->count()
        ]);
    }

    public function store(Request $request)
    {
        $AttachedFile = $this->AttachedFileService->createAttachedFile($request->all());
        return response()->json([
            'success' => true,
            'data' => $AttachedFile,
            'message' => 'Archivo adjunto creado exitosamente'
        ], 201);
    }

    public function show(AttachedFile $AttachedFile)
    {
        return response()->json([
            'success' => true,
            'data' => $AttachedFile,
            'message' => 'Archivo adjunto encontrado exitosamente'
        ]);
    }

    public function update(Request $request, AttachedFile $AttachedFile)
    {
        $attachedFile = AttachedFile::findOrFail($AttachedFile->id);
        $updatedAttachedFile = $this->AttachedFileService->updateAttachedFile($attachedFile, $request->all());
        return response()->json([
            'success' => true,
            'data' => new AttachedFileResource($updatedAttachedFile),
            'message' => 'Archivo adjunto actualizado exitosamente'
        ]);
    }

    public function destroy(AttachedFile $AttachedFile)
    {
        $this->AttachedFileService->deleteAttachedFile($AttachedFile);
        return response()->json(null, 204);
    }
}