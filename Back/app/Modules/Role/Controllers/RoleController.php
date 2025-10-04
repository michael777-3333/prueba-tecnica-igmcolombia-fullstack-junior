<?php

namespace App\Modules\Role\Controllers;

use App\Modules\Role\Models\Role;
use App\Modules\Role\Services\RoleService;
use App\Modules\Role\Resources\RoleResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $RoleService;

    public function __construct(RoleService $RoleService)
    {
        $this->RoleService = $RoleService;
    }

    public function index()
    {
        $Roles = $this->RoleService->getAllRoles();
        return RoleResource::collection($Roles);
    }

    public function store(Request $request)
    {
        $Role = $this->RoleService->createRole($request->all());
        return new RoleResource($Role);
    }

    public function show(Role $Role)
    {
        return new RoleResource($Role);
    }

    public function update(Request $request, Role $Role)
    {
        $Role = $this->RoleService->updateRole($Role, $request->all());
        return new RoleResource($Role);
    }

    public function destroy(Role $Role)
    {
        $this->RoleService->deleteRole($Role);
        return response()->json(null, 204);
    }
}