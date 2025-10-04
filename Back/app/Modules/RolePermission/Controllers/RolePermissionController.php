<?php

namespace App\Modules\RolePermission\Controllers;

use App\Modules\RolePermission\Models\RolePermission;
use App\Modules\RolePermission\Services\RolePermissionService;
use App\Modules\RolePermission\Resources\RolePermissionResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    protected $RolePermissionService;

    public function __construct(RolePermissionService $RolePermissionService)
    {
        $this->RolePermissionService = $RolePermissionService;
    }

    public function index()
    {
        $RolePermissions = $this->RolePermissionService->getAllRolePermissions();
        return RolePermissionResource::collection($RolePermissions);
    }

    public function store(Request $request)
    {
        $RolePermission = $this->RolePermissionService->createRolePermission($request->all());
        return new RolePermissionResource($RolePermission);
    }

    public function show(RolePermission $RolePermission)
    {
        return new RolePermissionResource($RolePermission);
    }

    public function update(Request $request, RolePermission $RolePermission)
    {
        $RolePermission = $this->RolePermissionService->updateRolePermission($RolePermission, $request->all());
        return new RolePermissionResource($RolePermission);
    }

    public function destroy(RolePermission $RolePermission)
    {
        $this->RolePermissionService->deleteRolePermission($RolePermission);
        return response()->json(null, 204);
    }
}