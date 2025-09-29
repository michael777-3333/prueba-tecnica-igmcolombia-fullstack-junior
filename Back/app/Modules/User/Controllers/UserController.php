<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Services\UserService;
use App\Modules\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
class UserController extends Controller
{
    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    public function index()
    {
        $users = $this->UserService->getAllUsers();
        return response()->json([
            'success' => true,
            'data' => $users,
            'count' => $users->count()
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->UserService->createUser($request->all());
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Usuario creado exitosamente'
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $user = $this->UserService->updateUser($user, $request->all());
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Usuario actualizado exitosamente'
        ]);
    }

    public function destroy(User $user)
    {
        $this->UserService->deleteUser($user);
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }
}