<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('register', 'UserController@register'); //register
Route::post('login', 'UserController@login'); //login


Route::middleware(['jwt.verify'])->group(function(){

    Route::get('login/check', 'UserController@LoginCheck'); //cek token
    Route::post('logout', "UserController@logout"); //logout

// Daily Scrum    
    Route::get('dailyscrum', 'DailyScrumController@index'); //read dailyscrum
    Route::get('dailyscrum/{limit}/{offset}', 'DailyScrumController@getAll'); //read dailyscrum
    Route::post('dailyscrum', 'DailyScrumController@store'); //create dailyscrum
	Route::put('dailyscrum/{id}', "DailyScrumController@update"); //update dailyscrum
	Route::delete('dailyscrum/{id}', "DailyScrumController@delete"); //delete dailyscrum

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
