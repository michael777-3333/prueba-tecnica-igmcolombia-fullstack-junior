<?php

namespace App\Modules\Permission\Controllers;

use App\Modules\Permission\Models\Permission;
use App\Modules\Permission\Services\PermissionService;
use App\Modules\Permission\Resources\PermissionResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $PermissionService;

    public function __construct(PermissionService $PermissionService)
    {
        $this->PermissionService = $PermissionService;
    }

    public function index()
    {
        $Permissions = $this->PermissionService->getAllPermissions();
        return PermissionResource::collection($Permissions);
    }

    public function store(Request $request)
    {
        $Permission = $this->PermissionService->createPermission($request->all());
        return new PermissionResource($Permission);
    }

    public function show(Permission $Permission)
    {
        return new PermissionResource($Permission);
    }

    public function update(Request $request, Permission $Permission)
    {
        $Permission = $this->PermissionService->updatePermission($Permission, $request->all());
        return new PermissionResource($Permission);
    }

    public function destroy(Permission $Permission)
    {
        $this->PermissionService->deletePermission($Permission);
        return response()->json(null, 204);
    }
}