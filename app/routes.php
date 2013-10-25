<?php

/**
 * Uploading photo
 */
Route::any('/upload/{album_id}', array(
    'before' => 'auth',
    'uses' => 'UploadController@upload'));

/**
 * Users authentification routes
 */

Route::get('/user/login', array('uses' => 'UsersController@login'));
Route::post('/user/login', array('uses' => 'UsersController@auhenticate'));

Route::get('/user/logout', array('uses' => 'UsersController@logout'));

Route::get('/user/register', array('uses' => 'UsersController@registration'));
Route::post('/user/register', array('uses' => 'UsersController@register'));

Route::get('/user/reset_password', array('uses' => 'UsersController@reset_password'));
Route::post('/user/reset_password', array('uses' => 'UsersController@reset_password'));

Route::get('/user/change_password/{reset_code}', array('uses' => 'UsersController@change_password'));
Route::post('/user/change_password/{reset_code}', array('uses' => 'UsersController@change_password'));

Route::get('/user/profile', array(
    'before' => 'auth',
    'uses' => 'UsersController@profile'));

/**
 * Gallery albums routes
 */
Route::get('gallery', array('uses' => 'AlbumsController@index'));

Route::get('album/{id}', array('uses' => 'AlbumsController@show'));

Route::any('gallery/new-album', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@create'
));