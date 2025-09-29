<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Modules\User\Controllers\UserController;
use App\Modules\Customer\Controllers\CustomerController;
use App\Modules\Invoice\Controllers\InvoiceController;
use App\Modules\InvoiceItem\Controllers\InvoiceItemController;
use App\Modules\AttachedFile\Controllers\AttachedFileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
    Route::put('/password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
});

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Usuarios
    Route::apiResource('users', UserController::class);
    
    // Clientes
    Route::apiResource('customers', CustomerController::class);
    
    // Facturas
    Route::apiResource('invoices', InvoiceController::class);
    
    // Items de factura
    Route::apiResource('invoice-items', InvoiceItemController::class);
    
    // Archivos adjuntos
    Route::apiResource('attached-files', AttachedFileController::class);
    
    // Dashboard/Estadísticas
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'totalUsers' => \App\Modules\User\Models\User::count(),
                'totalCustomers' => \App\Modules\Customer\Models\Customer::count(),
                'totalInvoices' => \App\Modules\Invoice\Models\Invoice::count(),
                'newUsersToday' => \App\Modules\User\Models\User::whereDate('created_at', today())->count(),
                'activeUsers' => \App\Modules\User\Models\User::where('created_at', '>=', now()->subDays(30))->count(),
            ]
        ]);
    });
});

// Ruta de prueba
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'data' => [
            'timestamp' => now(),
            'version' => '1.0.0',
            'status' => 'OK'
        ]
    ]);
});
