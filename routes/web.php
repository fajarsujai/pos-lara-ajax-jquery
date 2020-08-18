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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

	// category
	Route::resource('category','Admin\CategoryController');
	// Unit
	Route::resource('unit','Admin\UnitController');
	
	// start Product
	Route::resource('product','Admin\ProductController');
	// getcategori
	Route::post('product/getCategory','Admin\ProductController@getCategory')->name('product.getCategory');
	// end product

	// start product-stock
	Route::resource('product-stock','Admin\ProductStockController');
	Route::post('product-stock/getProduct','Admin\ProductStockController@getProduct')->name('product-stock.getProduct');
	


	// Stock Unit
	Route::resource('stock-unit','Admin\StockUnitController');
	Route::post('stock-unit/getProduct','Admin\StockUnitController@getProduct')->name('stock-unit.getProduct');
	Route::post('stock-unit/getUnit','Admin\StockUnitController@getUnit')->name('stock-unit.getUnit');
	Route::post('stock-unit/getStock','Admin\StockUnitController@getStock')->name('stock-unit.getStock');

	// start product-unit
	Route::resource('product-unit','Admin\ProductUnitController');
	Route::post('product-unit/getProduct','Admin\ProductUnitController@getProduct')->name('product-unit.getProduct');
	Route::post('product-unit/getUnit','Admin\ProductUnitController@getUnit')->name('product-unit.getUnit');
});

