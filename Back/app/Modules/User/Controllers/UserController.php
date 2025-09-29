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
        $Users = $this->UserService->getAllUsers();
        return Inertia::render('Users/Users', [
            'users' => $Users,
            'filters' => request()->all(['search', 'role']),
        ]);
    }

    public function store(Request $request)
    {
        $User = $this->UserService->createUser($request->all());
        return new UserResource($User);
    }

    public function show(User $User)
    {
        return new UserResource($User);
    }

    public function update(Request $request, User $User)
    {
        $User = $this->UserService->updateUser($User, $request->all());
        return new UserResource($User);
    }

    public function destroy(User $User)
    {
        $this->UserService->deleteUser($User);
        return response()->json(null, 204);
    }
}