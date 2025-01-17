<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CouturierAvailabilityController;
use App\Http\Controllers\API\ModelManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth:sanctum')->post('/api/user/availability', [CouturierAvailabilityController::class, 'update']);
