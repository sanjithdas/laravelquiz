<?php

use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\Admin\ResultsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ExamDashboardController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\ResultController;
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

// User routes

Route::group(['middleware' => ['auth:sanctum', 'verified']] , function () {
   Route::get('/dashboard',[ExamDashboardController::class,'index'])->name('dashboard');
});

Route::group(['middleware' => ['auth:sanctum', 'PreventBackHistory']], function () {
    Route::post('/start-exam', [ExamsController::class, 'start'])->name('start-exam');
    Route::post('/exam', [ExamsController::class, 'submitResponse'])->name('submit-response');

});

// Admin routes

//  dashboard routes 

Route::prefix('admin')->namespace('Admin')->middleware(['auth:sanctum','role:admin','PreventBackHistory'])->group(function () {
    Route::get('/dashboard',function(){
        return view('admin.index');
    })->name('admin.dashboard');

});

 

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth:sanctum','role:admin','UserPermissions']], function () {

    //User massdestroy
    Route::delete('users/destroy', 'UserController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UserController');

    // Question routes
    Route::delete('questions/destroy', 'QuestionsController@massDestroy')->name('questions.massDestroy');
    Route::resource('questions', 'QuestionsController');

    //categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    //options
    Route::delete('options/destroy', 'OptionsController@massDestroy')->name('options.massDestroy');
    Route::resource('options', 'OptionsController');

    //Results
    Route::post('results/user-result','ResultsController@getResults')->name('results.user_result');
    Route::get('results/incorrects/catid/user',  'ResultsController@getIncorrectAnswers')->name('results.incorrect');
    Route::get('results/unanswered/catid/user',  'ResultsController@getUnansweredQns')->name('results.unanswered');
    Route::resource('results', 'ResultsController');

   });

