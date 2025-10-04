<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        $user = Auth::user();

        // Verificar si el usuario tiene el permiso requerido
        if (!$user->hasPermission($permission)) {
            // Si es una petición AJAX, retornar error JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción.',
                    'required_permission' => $permission
                ], 403);
            }

            // Para peticiones normales, redirigir con mensaje de error
            return redirect()->back()->with('error', 'No tiene permisos para acceder a esta funcionalidad.');
        }

        return $next($request);
    }
}
