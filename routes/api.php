<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/token/session/get', [AppController::class, 'getCsrfToken']);
Route::post('/login/auth', [AppController::class, 'authentication']);

Route::get('/order/getData/{id}/{date}', [AppController::class, 'getOrders']);
Route::get('/user/getData', [AppController::class, 'getUsers']);

Route::post('/report/chemicalapplications', [AppController::class, 'setChemicalApplications']);
Route::post('/report/devices', [AppController::class, 'setDevices']);
