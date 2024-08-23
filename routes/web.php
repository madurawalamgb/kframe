<?php

use App\Models\Form;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('forms','\App\Http\Controllers\FormController');
Route::post('forms/{form}', '\App\Http\Controllers\FormController@generate')->name('forms.generate');
Route::resource('fields','\App\Http\Controllers\FieldController');

Route::resource('tests', '\App\Http\Controllers\TestController');
Route::resource('customers', '\App\Http\Controllers\CustomerController');

Route::resource('reaches', '\App\Http\Controllers\ReachController');