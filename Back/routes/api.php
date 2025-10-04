<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Modules\User\Controllers\UserController;
use App\Modules\Customer\Controllers\CustomerController;
use App\Modules\Invoice\Controllers\InvoiceController;
use App\Modules\InvoiceItem\Controllers\InvoiceItemController;
use App\Modules\AttachedFile\Controllers\AttachedFileController;
use App\Modules\Item\Controllers\ItemController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Ruta de prueba/health check
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

// Rutas de autenticación (sin middleware)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
    Route::put('/password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
});

// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    // USUARIOS
    Route::middleware(['auth', 'permission:usuarios'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });
    });
    // CLIENTES
    Route::middleware(['auth', 'permission:clientes'])->group(function () {
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{customer}', [CustomerController::class, 'show']);
        Route::put('/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
    });
    });
    // FACTURAS
    Route::middleware(['auth', 'permission:facturas'])->group(function () {
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::get('/{invoice}', [InvoiceController::class, 'show']);
        Route::put('/{invoice}', [InvoiceController::class, 'update']);
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy']);

        // Rutas adicionales para facturas
        Route::get('/{invoice}/items', [InvoiceController::class, 'getItems']);
        Route::post('/{invoice}/items', [InvoiceController::class, 'addItem']);
        Route::get('/{invoice}/files', [InvoiceController::class, 'getFiles']);
        Route::post('/{invoice}/files', [InvoiceController::class, 'addFile']);
    });
    });
    // ITEMS DE FACTURA
    Route::middleware(['auth', 'permission:items'])->group(function () {
    Route::prefix('invoice-items')->group(function () {
        Route::get('/', [InvoiceItemController::class, 'index']);
        Route::post('/', [InvoiceItemController::class, 'store']);
        Route::get('/{invoiceItem}', [InvoiceItemController::class, 'show']);
        Route::put('/{invoiceItem}', [InvoiceItemController::class, 'update']);
        Route::delete('/{invoiceItem}', [InvoiceItemController::class, 'destroy']);
    });
    });

    // ARCHIVOS ADJUNTOS
    Route::middleware(['auth', 'permission:archivos-adjuntos'])->group(function () {
    Route::prefix('attached-files')->group(function () {
        Route::get('/', [AttachedFileController::class, 'index']);
        Route::post('/', [AttachedFileController::class, 'store']);
        Route::get('/{attachedFile}', [AttachedFileController::class, 'show']);
        Route::put('/{attachedFile}', [AttachedFileController::class, 'update']);
        Route::delete('/{attachedFile}', [AttachedFileController::class, 'destroy']);
    });
    });
    // ITEMS
    Route::middleware(['auth', 'permission:items'])->group(function () {
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{item}', [ItemController::class, 'show']);
        Route::put('/{item}', [ItemController::class, 'update']);
        Route::delete('/{item}', [ItemController::class, 'destroy']);
    });
    });
    // DASHBOARD/ESTADÍSTICAS
    Route::middleware(['auth', 'permission:dashboard'])->group(function () {
    Route::get('/dashboard/stats', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'totalUsers' => \App\Modules\User\Models\User::count(),
                'totalCustomers' => \App\Modules\Customer\Models\Customer::count(),
                'totalInvoices' => \App\Modules\Invoice\Models\Invoice::count(),
                'totalRevenue' => \App\Modules\Invoice\Models\Invoice::where('status', 'paid')->sum('total_amount'),
                'pendingInvoices' => \App\Modules\Invoice\Models\Invoice::where('status', 'pending')->count(),
            ]
        ]);
    });
    });
});
