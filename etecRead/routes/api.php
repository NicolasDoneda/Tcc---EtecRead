<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\MyLoansController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\LoansController as AdminLoansController;
use App\Http\Controllers\Api\Admin\ReservationsController as AdminReservationsController;
use App\Http\Controllers\Api\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes - Mobile App
|--------------------------------------------------------------------------
*/

// ============================================
// ROTAS PÚBLICAS (Sem autenticação)
// ============================================

Route::post('/login', [AuthController::class, 'login']);

// ✅ CATÁLOGO PÚBLICO (pode ver SEM login)
Route::prefix('catalog')->group(function () {
    Route::get('/', [CatalogController::class, 'index']);
    Route::get('/books/{id}', [CatalogController::class, 'show']);
    Route::get('/statistics', [CatalogController::class, 'statistics']);
    Route::get('/categories', [CatalogController::class, 'categories']);
    Route::get('/authors', [CatalogController::class, 'authors']);
    Route::get('/authors/{id}', [CatalogController::class, 'showAuthor']);
    Route::post('/search', [CatalogController::class, 'advancedSearch']);
});

// Rota de teste
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Biblioteca Mobile está funcionando!',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// ============================================
// ROTAS AUTENTICADAS (Requer token Sanctum)
// ============================================

Route::middleware(['auth:sanctum'])->group(function () {

    // ==================== AUTH ====================
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);

    // ==================== ALUNO ====================
    
    // Meus Empréstimos
    Route::prefix('my-loans')->group(function () {
        Route::get('/active', [MyLoansController::class, 'active']);
        Route::get('/history', [MyLoansController::class, 'history']);
        Route::get('/summary', [MyLoansController::class, 'summary']);
    });

    // ==================== ADMIN ====================
    
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Empréstimos
        Route::prefix('loans')->group(function () {
            Route::get('/', [AdminLoansController::class, 'index']);
            Route::get('/statistics', [AdminLoansController::class, 'statistics']);
        });

        // Reservas
        Route::prefix('reservations')->group(function () {
            Route::get('/', [AdminReservationsController::class, 'index']);
            Route::get('/statistics', [AdminReservationsController::class, 'statistics']);
            Route::get('/{id}', [AdminReservationsController::class, 'show']);
        });

        // Relatórios
        Route::prefix('reports')->group(function () {
            Route::get('/monthly', [ReportController::class, 'monthly']);
            Route::get('/overview', [ReportController::class, 'overview']);
            Route::get('/download-pdf', [ReportController::class, 'downloadPDF']);
        });
    });
});