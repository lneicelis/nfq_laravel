<?php

/**
 * Uploading photo
 */
Route::get('/upload/{album_id}', array(
    'before' => 'auth',
    'uses' => 'UploadController@upload'));

Route::post('/upload/{album_id}', array(
    'before' => 'auth',
    'uses' => 'UploadController@process'));

/**
 * Users authentification routes
 */

Route::any('/user/login', array('uses' => 'UsersController@login'));

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
Route::get('/', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@index'));

Route::get('gallery', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@index'));

Route::any('gallery/new-album', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@create'
));

/**
 * Albums routes
 */
Route::get('album/{id}', array('uses' => 'AlbumsController@show'));

Route::get('album/set_cover/{photo_id}', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@setCover'));

Route::post('album/edit', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@edit'));
/**
 * Photos routes
 */
Route::get('photo/destory/{id}', array(
    'before' => 'auth',
    'uses' => 'PhotosController@destroy'));
