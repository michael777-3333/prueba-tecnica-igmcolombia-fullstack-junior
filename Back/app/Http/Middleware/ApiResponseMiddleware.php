<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Si la respuesta es un error de autenticación, formatear la respuesta
        if ($response->getStatusCode() === 401) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autenticación inválido o expirado',
                'error' => 'Unauthenticated'
            ], 401);
        }

        return $response;
    }
}
