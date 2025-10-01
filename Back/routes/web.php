<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API Laravel funcionando correctamente',
        'data' => [
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api/test' => 'Prueba de API',
                'POST /api/auth/login' => 'Login de usuario',
                'POST /api/auth/register' => 'Registro de usuario',
                'GET /api/auth/me' => 'Usuario actual (requiere token)',
                'GET /api/users' => 'Listar usuarios (requiere token)',
                'GET /api/customers' => 'Listar clientes (requiere token)',
                'GET /api/invoices' => 'Listar facturas (requiere token)',
                'GET /api/dashboard/stats' => 'Estadísticas (requiere token)'
            ],
            'documentation' => 'Ver README.md para más información'
        ]
    ]);
});

// Redirigir cualquier ruta no encontrada a la API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint no encontrado',
        'data' => [
            'available_endpoints' => '/api/*'
        ]
    ], 404);
});