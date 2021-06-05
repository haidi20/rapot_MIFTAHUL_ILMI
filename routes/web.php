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

Route::get('/', 'DashboardController@index');

Route::group(['prefix' => 'master'], function() {
    // Route::get('class', 'MasterClassController@index');
    Route::group(['prefix' => 'class'], function() {
        Route::get('', 'MasterClassController@index')->name("masterClass");
        Route::get('ajaxRead', 'MasterClassController@ajaxRead');
        Route::get('ajaxReadTypeahead', 'MasterClassController@ajaxReadTypeahead');
        Route::get('create', 'MasterClassController@create')->name('masterClass.create');
        Route::get('edit/{id}', 'MasterClassController@edit')->name('masterClass.edit');
        Route::post('store', 'MasterClassController@store')->name('masterClass.store');
        Route::post('update/{id}', 'MasterClassController@update')->name('masterClass.update');
        Route::get('delete/{id}', 'MasterClassController@delete')->name('masterClass.delete');
    });
});
