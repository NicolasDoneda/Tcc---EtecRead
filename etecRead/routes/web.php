<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\BookWebController;
use App\Http\Controllers\Web\LoanWebController;
use App\Http\Controllers\Web\AdminController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Alunos + Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Livros
    Route::get('/livros', [BookWebController::class, 'index'])->name('livros.index');
    Route::get('/livros/{id}', [BookWebController::class, 'show'])->name('livros.show');
    
    // Empréstimos do usuário
    Route::get('/meus-emprestimos', [LoanWebController::class, 'myLoans'])->name('emprestimos.meus');
    
    // Reservas do usuário
    Route::get('/minhas-reservas', [LoanWebController::class, 'myReservations'])->name('reservas.minhas');
    
    /*
    |--------------------------------------------------------------------------
    | Rotas Admin
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Livros
        Route::get('/livros', [AdminController::class, 'books'])->name('livros.index');
        Route::get('/livros/criar', [AdminController::class, 'createBook'])->name('livros.create');
        Route::post('/livros', [AdminController::class, 'storeBook'])->name('livros.store');
        Route::get('/livros/{id}/editar', [AdminController::class, 'editBook'])->name('livros.edit');
        Route::put('/livros/{id}', [AdminController::class, 'updateBook'])->name('livros.update');
        Route::delete('/livros/{id}', [AdminController::class, 'destroyBook'])->name('livros.destroy');
        
        // Categorias
        Route::get('/categorias', [AdminController::class, 'categories'])->name('categorias.index');
        Route::get('/categorias/criar', [AdminController::class, 'createCategory'])->name('categorias.create');
        Route::post('/categorias', [AdminController::class, 'storeCategory'])->name('categorias.store');
        Route::get('/categorias/{id}/editar', [AdminController::class, 'editCategory'])->name('categorias.edit');
        Route::put('/categorias/{id}', [AdminController::class, 'updateCategory'])->name('categorias.update');
        Route::delete('/categorias/{id}', [AdminController::class, 'destroyCategory'])->name('categorias.destroy');
        
        // Usuários
        Route::get('/usuarios', [AdminController::class, 'users'])->name('usuarios.index');
        Route::get('/usuarios/criar', [AdminController::class, 'createUser'])->name('usuarios.create');
        Route::post('/usuarios', [AdminController::class, 'storeUser'])->name('usuarios.store');
        Route::get('/usuarios/{id}/editar', [AdminController::class, 'editUser'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [AdminController::class, 'updateUser'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [AdminController::class, 'destroyUser'])->name('usuarios.destroy');
        
        // Empréstimos
        Route::get('/emprestimos', [AdminController::class, 'loans'])->name('emprestimos.index');
        Route::get('/emprestimos/criar', [AdminController::class, 'createLoan'])->name('emprestimos.create');
        Route::post('/emprestimos', [AdminController::class, 'storeLoan'])->name('emprestimos.store');
        Route::get('/emprestimos/{id}/editar', [AdminController::class, 'editLoan'])->name('emprestimos.edit');
        Route::put('/emprestimos/{id}', [AdminController::class, 'updateLoan'])->name('emprestimos.update');
        Route::delete('/emprestimos/{id}', [AdminController::class, 'destroyLoan'])->name('emprestimos.destroy');
        
        // Comandos
        Route::post('/promover-alunos', [AdminController::class, 'promoteStudents'])->name('promover');
        Route::post('/deletar-formandos', [AdminController::class, 'deleteGraduated'])->name('deletar-formandos');
    });
});