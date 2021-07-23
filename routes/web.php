<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'MainController@index')->name('index');
Route::post('/sendmail', 'MainController@sendmail')->name('sendmail');
Route::post('/changeFolder', 'MainController@changeFolder')->name('changeFolder');
Route::post('/deleteMail', 'MainController@deleteMail')->name('deleteMail');
Route::post('/showMail', 'MainController@showMail')->name('showMail');
Route::post('/moveToArchive', 'MainController@moveToArchive')->name('moveToArchive');

Route::get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	Artisan::call('route:clear');
	return "Кэш очищен";
});