<?php

use App\Http\Controllers\AppConfigController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',                             [HomeController::class, 'index'])->name('home');

Route::post('/login',                       [AuthController::class, 'login'])->name('login');
Route::get('/logout',                       [AuthController::class, 'logout'])->name('logout');

Route::middleware(['is.authenticated'])->group(function() {
    Route::get('/dashboard',                [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/config',                   [DashboardController::class, 'config'])->name('config');
    Route::post('/first-access',            [DashboardController::class, 'firstAccess'])->name('first-access');
    Route::get('/initial',                  [DashboardController::class, 'initial'])->name('initial');

    Route::get('/app/url',                  [DashboardController::class, 'appUrl']);

    Route::get('/categories',               [CategorieController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}',          [CategorieController::class, 'show']);
    Route::post('/categories/store',        [CategorieController::class, 'store']);
    Route::post('/categories/update',       [CategorieController::class, 'update']);
    Route::get('/categories/destroy/{id}',  [CategorieController::class, 'destroy']);

    Route::get('/cards',                    [CreditCardController::class, 'index'])->name('cards.index');
    Route::post('/cards/store',             [CreditCardController::class, 'store']);
    Route::get('/cards/{id}',               [CreditCardController::class, 'show']);
    Route::post('/cards/update',            [CreditCardController::class, 'update']);
    Route::get('/cards/destroy/{id}',       [CreditCardController::class, 'destroy']);

    Route::get('/users',                    [UserController::class, 'index'])->name('users.index');
    Route::get('/users/password/generate',  [UserController::class, 'passwordGenerate']);
    Route::get('/users/{id}',               [UserController::class, 'show']);
    Route::post('/users/store',             [UserController::class, 'store']);
    Route::post('/users/update',             [UserController::class, 'update']);

    Route::get('/recipes',                  [RecipesController::class, 'index']);
    Route::get('/recipes/{id}',             [RecipesController::class, 'show']);
    Route::post('/recipes/store',           [RecipesController::class, 'store']);
    Route::post('/recipes/update',          [RecipesController::class, 'update']);
    Route::get('/recipes/destroy/{id}',     [RecipesController::class, 'destroy'])->name('recipe.destroy');

    Route::get('/expenses',                 [ExpensesController::class, 'index']);
    Route::get('/expenses/{id}',            [ExpensesController::class, 'show']);
    Route::post('/expenses/store',          [ExpensesController::class, 'store']);
    Route::post('/expenses/update',         [ExpensesController::class, 'update']);
    Route::get('/expenses/destroy/{id}',    [ExpensesController::class, 'destroy']);
    Route::post('/expenses/revert/{id}',    [ExpensesController::class, 'revert']);
    Route::post('/expenses/verify/payment', [ExpensesController::class, 'verifyMetodPayment']);
    Route::post('/expenses/cancel/one',     [ExpensesController::class, 'cancelOneInstallment']);
    Route::post('/expenses/cancel/all',     [ExpensesController::class, 'cancelAllInstallments']);

    Route::post('/invoices/pay',            [InvoicesController::class, 'pay']);

    Route::get('/charts/index',             [ChartController::class, 'index']);

    Route::post('/config/store',            [AppConfigController::class, 'store']);
});
