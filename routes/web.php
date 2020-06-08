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

Route::get('/', 'TableController@index')->name('table');

Route::get('/orders', 'TableController@orders')->name('orders');
Route::post('/orders/new', 'TableController@new')->name('orders.new');
Route::delete('/orders', 'TableController@reset')->name('orders.reset');
