<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/insertanatomy', [AuthController::class, 'anatomy']);
    Route::post('/insertxray', [AuthController::class, 'xray']);  
    Route::post('/deletexray', [AuthController::class, 'deletexray']);      
    Route::post('/deleteanatomy', [AuthController::class, 'deleteanatomy']);
});
Route::get('/users',[UserController::class,'index']);
Route::post('/doctorlogin',[UserController::class,'doctorlogin']);
Route::post('/userupdate/{id}',[UserController::class,'userupdate']);
Route::post('/answerquestions/{id}', [UserController::class, 'questions']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
