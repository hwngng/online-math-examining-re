<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes([
    'confirm' => false,
    'reset' => false
]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/ckeditor', function () {
    return view('ckeditor');
});

Route::group(['middleware' => ['auth']], function () {

    Route::name('teacher.')
        ->prefix('teacher')
        ->middleware('authorize:admin,teacher')
        ->group(function () {
            Route::name('index')->get('/', 'TeacherController@index');
            Route::name('question.')->prefix('question')->group(function () {
                Route::name('list')->get('/', 'QuestionController@index');
                Route::name('create')->get('/create', 'QuestionController@create')->middleware('authorize:teacher');
                Route::name('get')->get('/{id}', 'QuestionController@getById');
                Route::name('store')->post('/', 'QuestionController@store')->middleware('authorize:teacher');
                Route::name('destroy')->get('/destroy/{id}', 'QuestionController@destroy')->middleware('authorize:teacher');
                Route::name('edit')->get('/edit/{id}', 'QuestionController@edit')->middleware('authorize:teacher');
                Route::name('update')->post('/update', 'QuestionController@update')->middleware('authorize:teacher');
            });

            Route::name('test.')->prefix('test')->group(function () {
                Route::name('list')->get('/', 'TestController@index');
                Route::name('create')->get('/create', 'TestController@create')->middleware('authorize:teacher');
                Route::name('store')->post('/store', 'TestController@store')->middleware('authorize:teacher');
                Route::name('edit')->get('/edit/{id}', 'TestController@edit')->middleware('authorize:teacher');
                Route::name('update')->post('/update', 'TestController@update')->middleware('authorize:teacher');
            });
            Route::name('result.')->prefix('result')->group(function () {
                Route::name('list')->get('/', 'WorkHistoryController@showAllTestResult')->middleware('authorize:teacher');
                Route::name('detail')->get('/result/{testId}', 'WorkHistoryController@getStudentResultByTestId')->middleware('authorize:teacher');
            });
        });

    Route::name('admin.')
        ->prefix('admin')
        ->middleware('authorize:admin')
        ->group(function () {
            Route::name('index')->get('/', 'AdminController@index');
            Route::name('user.')->prefix('user')->group(function () {
                Route::name('list')->get('/', 'UserController@index');
                Route::name('create')->post('/create', 'UserController@createUser')->middleware('authorize:admin');
                Route::name('destroy')->get('/destroy/{id}', 'UserController@destroy')->middleware('authorize:admin');
                Route::name('update')->post('/update/{id}', 'UserController@update')->middleware('authorize:admin');
            });
        });


    Route::name('student.')
        ->prefix('student')
        ->middleware('authorize:student')
        ->group(function () {
            Route::name('index')->get('/', 'StudentController@index');
            Route::name('about')->get('/about/{id}', 'StudentController@about');
            Route::name('test.')->prefix('test')->group(function () {
                Route::name('list')->get('/list', 'StudentController@getAllAvailableTests');
                Route::name('start')->get('/{id}', 'WorkHistoryController@startTest')->middleware('authorize:student');
                Route::name('update')->post('/update/{id}', 'WorkHistoryController@updateTestResult')->middleware('authorize:student');
                Route::name('finish')->post('/finish', 'WorkHistoryController@completeTest')->middleware('authorize:student');
            });


            Route::name('result.')->prefix('result')->group(function () {
                Route::name('detail')->get('/{userId}/{testId}', 'WorkHistoryController@getResultByTestIdAnduserId')->middleware('authorize:student');
                Route::name('list')->get('/{userId}', 'WorkHistoryController@getStudentResultByUserId')->middleware('authorize:student');
            });
        });
});
