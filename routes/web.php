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

// Tour
Route::get('tour', 'TourController@index');
Route::get('tour/{id}', 'TourController@show');
Route::get('tour/valid/{name}', 'TourController@valid');
Route::post('tour/status', 'TourController@status');
Route::post('tour', 'TourController@store');
Route::patch('tour/{id}', 'TourController@update');
Route::resource('tour', 'TourController');

// Booking
Route::get('booking', 'BookingController@index');
Route::get('booking/tour/{id}', 'TourController@showEnable');
Route::post('booking', 'BookingController@store');
