<?php

Route::post('test',array(
    'before' => 'csrf',
    function(){}));


/**
 * Users authentification routes
 */
Route::get('/user/login',array(
    'uses' => 'UsersController@getLogin'));
Route::post('/user/login', array(
    'uses' => 'UsersController@postLogin'));

Route::get('/user/logout', array(
    'uses' => 'UsersController@getLogout'));
Route::post('/user/logout', array(
    'uses' => 'UsersController@postLogout'));

Route::get('/user/register', array(
    'uses' => 'UsersController@getRegister'));
Route::post('/user/register', array(
    'uses' => 'UsersController@postRegister'));

Route::get('/user/reset_password', array(
    'uses' => 'UsersController@getResetPassword'));
Route::post('/user/reset_password', array(
    'uses' => 'UsersController@postResetPassword'));

Route::get('/user/change_password/{reset_code}', array(
    'uses' => 'UsersController@getChangePassword'));
Route::post('/user/change_password/{reset_code}', array(
    'uses' => 'UsersController@postChangePassword'));

Route::get('/user/profile', array(
    'before' => 'auth',
    'uses' => 'UsersController@getProfile'));

/**
 * Gallery albums routes
 */
Route::get('/', array(
    'as' => 'gallery',
    'before' => 'auth',
    'uses' => 'AlbumsController@index'));

/**
 * Albums routes
 */

Route::post('gallery/new-album', array(
    'before' => 'auth|csrf',
    'uses' => 'AlbumsController@postCreate'));

Route::get('album/{id}', array(
    'as' => 'album',
    'uses' => 'AlbumsController@show'));

Route::post('album/set_cover', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@postSetCover'));

Route::post('album/edit', array(
    'before' => 'auth|csrf',
    'uses' => 'AlbumsController@postEdit'));

Route::get('album/destroy/{album_id}', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@destroy'));

/**
 * Photos routes
 */

Route::get('/upload/{album_id}', array(
    'before' => 'auth',
    'uses' => 'PhotosController@getUpload'));

Route::post('/upload/{album_id}', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postUpload'));

Route::post('photo/destory', array(
    'before' => 'auth|csrf',
    'uses' => 'PhotosController@destroy'));

Route::post('photo/edit', array(
    'before' => 'auth',
    'uses' => 'PhotosController@edit'));

Route::post('photo/transfer', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postTransfer'));

Route::post('photo/get-all', array(
    'before' => 'auth',
    'uses' => 'PhotosController@getPhotos'));

Route::post('photo/crop', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postCrop'));

Route::post('photo/rotate/{direction}', array(
    'before' => 'auth|csrf',
    'uses' => 'PhotosController@postRotate'));

Route::post('photo/status', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postStatus'));
