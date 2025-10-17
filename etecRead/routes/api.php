<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\LoanController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Registro de alunos (role = aluno)
Route::post('/register', function(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'rm' => 'nullable|string|unique:users|max:50',
        'ano_escolar' => 'required|in:1,2,3',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'rm' => $request->rm,
        'password' => Hash::make($request->password),
        'role' => 'aluno',
        'ano_escolar' => $request->ano_escolar,
    ]);

    return response()->json($user, 201);
});

// Login (todos usuários)
Route::post('/login', function(Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'role' => $user->role, 'user' => $user]);
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas pelo Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // ================== ALUNOS ==================
    // Apenas visualização: index e show
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{id}', [BookController::class, 'show']);

    Route::get('reservations', [ReservationController::class, 'index']);
    Route::get('reservations/{id}', [ReservationController::class, 'show']);

    Route::get('loans', [LoanController::class, 'index']);
    Route::get('loans/{id}', [LoanController::class, 'show']);

    // ================== ADMINS ==================
    Route::middleware([IsAdmin::class])->group(function () {
        // Users
        Route::apiResource('users', UserController::class);

        // Categories
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Books
        Route::post('books', [BookController::class, 'store']);
        Route::put('books/{id}', [BookController::class, 'update']);
        Route::delete('books/{id}', [BookController::class, 'destroy']);

        // Reservations
        Route::post('reservations', [ReservationController::class, 'store']);
        Route::put('reservations/{id}', [ReservationController::class, 'update']);
        Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);

        // Loans
        Route::post('loans', [LoanController::class, 'store']);
        Route::put('loans/{id}', [LoanController::class, 'update']);
        Route::delete('loans/{id}', [LoanController::class, 'destroy']);

        // Comandos manuais
        Route::post('admin/promote-students', function() {
            \Artisan::call('students:promote');
            $output = \Artisan::output();
            
            return response()->json([
                'message' => 'Promoção de alunos executada',
                'output' => $output
            ]);
        });

        Route::post('admin/delete-graduated-students', function() {
            \Artisan::call('students:delete-graduated');
            $output = \Artisan::output();
            
            return response()->json([
                'message' => 'Exclusão de formandos executada',
                'output' => $output
            ]);
        });
    });
});