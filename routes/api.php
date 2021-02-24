<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;// manggil controller sesuai bawaan laravel 8
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', 'AuthController@login');
Route::post('register', 'UserController@register');
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    // manggil controller sesuai bawaan laravel 8
    Route::post('logout', [AuthController::class, 'logout']);
    // manggil controller dengan mengubah namespace di RouteServiceProvider.php biar bisa kayak versi2 sebelumnya
    Route::get('profil', 'UserController@profil');
    Route::patch('updateprofil', 'UserController@updateprofil');
    Route::patch('updatepassword', 'UserController@updatepassword');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin']], function() {
    Route::patch('status/{id}', 'UserController@status');
    Route::get('user', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin,mitra']], function() {
    Route::resource('produk', 'ProdukController');
});

