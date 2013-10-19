<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('upload.upload-form');
});

Route::get('/upload', array('uses' => 'UploadController@upload'));
Route::post('/upload', array('uses' => 'UploadController@process'));

/**
 * Users authentification routes
 */

Route::get('/user/login', array('uses' => 'UsersController@login'));
Route::post('/user/login', array('uses' => 'UsersController@auhenticate'));

Route::get('/user/logout', array('uses' => 'UsersController@logout'));

Route::get('/user/register', array('uses' => 'UsersController@registration'));
Route::post('/user/register', array('uses' => 'UsersController@register'));

Route::get('/user/profile', array('uses' => 'UsersController@profile'));