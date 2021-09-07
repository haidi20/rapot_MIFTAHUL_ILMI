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
        Route::post('store', 'MasterClassController@store')->name('masterClass.store');
        Route::post('update/{id}', 'MasterClassController@update')->name('masterClass.update');
        Route::get('delete/{id}', 'MasterClassController@delete')->name('masterClass.delete');
    });

    Route::group(['prefix' => 'student'], function() {
        Route::get('', 'MasterStudentController@index')->name("masterStudent");
        Route::get('ajaxRead', 'MasterStudentController@ajaxRead');
        Route::get('ajaxReadTypeahead', 'MasterStudentController@ajaxReadTypeahead');
        Route::post('store', 'MasterStudentController@store')->name('masterStudent.store');
        Route::post('update/{id}', 'MasterStudentController@update')->name('masterStudent.update');
        Route::get('delete/{id}', 'MasterStudentController@delete')->name('masterStudent.delete');
    });

    Route::group(['prefix' => 'absen-type'], function() {
        Route::get('', 'MasterAbsenTypeController@index')->name("masterAbsenType");
        Route::get('ajaxRead', 'MasterAbsenTypeController@ajaxRead');
        Route::get('ajaxReadTypeahead', 'MasterAbsenTypeController@ajaxReadTypeahead');
        Route::post('store', 'MasterAbsenTypeController@store')->name('masterAbsenType.store');
        Route::post('update/{id}', 'MasterAbsenTypeController@update')->name('masterAbsenType.update');
        Route::get('delete/{id}', 'MasterAbsenTypeController@delete')->name('masterAbsenType.delete');
    });

    Route::group(['prefix' => 'quiz'], function() {
        Route::get('', 'MasterQuizController@index')->name("masterQuiz");
        Route::get('ajaxRead', 'MasterQuizController@ajaxRead');
        Route::get('ajaxReadTypeahead', 'MasterQuizController@ajaxReadTypeahead');
        Route::post('store', 'MasterQuizController@store')->name('masterQuiz.store');
        Route::post('update/{id}', 'MasterQuizController@update')->name('masterQuiz.update');
        Route::get('delete/{id}', 'MasterQuizController@delete')->name('masterQuiz.delete');
    });
});

Route::group(['prefix' => 'student-active'], function() {
    Route::get('/', 'StudentActiveController@index')->name('studentActive.index');
    Route::get('ajaxRead', 'StudentActiveController@ajaxRead');
    Route::post('store-student', 'StudentActiveController@storeStudent')->name('studentActive.storeStudent');
});

Route::group(['prefix' => 'quiz-student'], function() {
    Route::get('', 'QuizStudentController@index')->name("quizStudent");
    Route::get('ajaxRead', 'QuizStudentController@ajaxRead');
    Route::get('ajaxReadTypeahead', 'QuizStudentController@ajaxReadTypeahead');
    Route::get('ajaxReadDateAbsen', 'QuizStudentController@ajaxReadDateAbsen');

    // Route::post('update/{id}', 'QuizStudentController@update')->name('quizStudent.update');
    Route::get('delete/{id}', 'QuizStudentController@delete')->name('quizStudent.delete');
});

Route::group(['prefix' => 'absen'], function() {
    Route::get('ajaxRead', 'AbsenController@ajaxRead');
    Route::post('ajaxSave', 'AbsenController@ajaxSave');
});

Route::group(['prefix' => 'report'], function() {
    Route::get('', 'ReportController@index');
    Route::get('ajaxRead', 'ReportController@ajaxRead');
    Route::get('print', 'ReportController@print');
});
