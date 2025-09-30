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
            'data' => AttachedFileResource::collection($AttachedFiles),
            'message' => 'Archivos adjuntos encontrados exitosamente'
        ]);
    }

    public function store(Request $request)
    {
        $AttachedFile = $this->AttachedFileService->createAttachedFile($request->all());
        return response()->json([
            'success' => true,
            'data' => new AttachedFileResource($AttachedFile),
            'message' => 'Archivo adjunto creado exitosamente'
        ], 201);
    }

    public function show($id)
    {
        $AttachedFile = AttachedFile::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new AttachedFileResource($AttachedFile),
            'message' => 'Archivo adjunto encontrado exitosamente'
        ]);
    }

    public function update(Request $request, $id)
    {
        $AttachedFile = AttachedFile::findOrFail($id);
        $updatedAttachedFile = $this->AttachedFileService->updateAttachedFile($AttachedFile, $request->all());
        return response()->json([
            'success' => true,
            'data' => new AttachedFileResource($updatedAttachedFile),
            'message' => 'Archivo adjunto actualizado exitosamente'
        ]);
    }

    public function destroy($id)
    {
        $AttachedFile = AttachedFile::findOrFail($id);
        $this->AttachedFileService->deleteAttachedFile($AttachedFile);
        return response()->json([
            'success' => true,
            'message' => 'Archivo adjunto eliminado exitosamente'
        ]);
    }
}