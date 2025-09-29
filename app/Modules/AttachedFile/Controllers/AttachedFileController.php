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
        return AttachedFileResource::collection($AttachedFiles);
    }

    public function store(Request $request)
    {
        $AttachedFile = $this->AttachedFileService->createAttachedFile($request->all());
        return new AttachedFileResource($AttachedFile);
    }

    public function show(AttachedFile $AttachedFile)
    {
        return new AttachedFileResource($AttachedFile);
    }

    public function update(Request $request, AttachedFile $AttachedFile)
    {
        $AttachedFile = $this->AttachedFileService->updateAttachedFile($AttachedFile, $request->all());
        return new AttachedFileResource($AttachedFile);
    }

    public function destroy(AttachedFile $AttachedFile)
    {
        $this->AttachedFileService->deleteAttachedFile($AttachedFile);
        return response()->json(null, 204);
    }
}