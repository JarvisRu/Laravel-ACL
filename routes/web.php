<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('home/admin')->group(function () {
    Route::get('/', 'HomeController@getAdminPage')->name('adminPage');

    Route::middleware(['checkRole:Admin'])->group(function () {
        Route::post('add_role', 'HomeController@addRole');
        Route::post('add_permission', 'HomeController@addPermission');
    });

    Route::post('assign_role', 'HomeController@assignRole')->middleware('checkPermission:賦予角色');
    Route::post('assign_permission', 'HomeController@assignPermission')->middleware('checkPermission:賦予權限');
    
});

