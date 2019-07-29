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
    return view('main');
});

Route::get('/tour', 'TourController@index');
Route::get('/tour/{id}', 'TourController@show');
Route::get('/tour/valid/{name}', 'TourController@valid');
Route::get('/Booking', 'BookingController@index');
Route::post('tour', 'TourController@create');
Route::patch('tour/{id}', 'TourController@update');
Route::resource('/tour', 'TourController');
// Route::delete('tour/{id}', 'TourController@delete');