<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UtilisateurController;
use App\Http\Controllers\API\TailorAPIController;
use App\Http\Controllers\API\ModelManagementController;
// Routes Models
Route::get('/models',[ModelManagementController::class,'index'])->name('models.index');
Route::post('/models', [ModelManagementController::class, 'store']);
Route::get('/categories',[ModelManagementController::class,'indexCategorie']);
Route::get('/utilisateurs',[ModelManagementController::class,'indexUtilisateur']);

// Routes publiques
Route::post('/register', [UtilisateurController::class, 'store']);
Route::post('/login', [UtilisateurController::class, 'login']);

// Routes protégées par l'authentification
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UtilisateurController::class, 'getUserData']);
    Route::post('/user/photo', [UtilisateurController::class, 'uploadPhoto']);
});

Route::get('/couturiers', [TailorAPIController::class, 'index']);
