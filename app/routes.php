<?php

/**
 * Users authentification routes
 */
Route::get('/user/login',array(
    'uses' => 'UsersController@getLogin'));
Route::post('/user/login', array(
    'before' => 'csrf',
    'uses' => 'UsersController@postLogin'));

Route::get('/user/logout', array(
    'uses' => 'UsersController@getLogout'));
Route::post('/user/logout', array(
    'uses' => 'UsersController@postLogout'));

Route::get('/user/register', array(
    'uses' => 'UsersController@getRegister'));
Route::post('/user/register', array(
    'before' => 'csrf',
    'uses' => 'UsersController@postRegister'));

Route::get('/user/reset_password', array(
    'uses' => 'UsersController@getResetPassword'));
Route::post('/user/reset_password', array(
    'before' => 'csrf',
    'uses' => 'UsersController@postResetPassword'));

Route::get('/user/change_password/{reset_code}', array(
    'uses' => 'UsersController@getChangePassword'));
Route::post('/user/change_password/{reset_code}', array(
    'before' => 'csrf',
    'uses' => 'UsersController@postChangePassword'));

Route::get('/user/all', array(
    'before' => 'admin',
    'uses' => 'UsersController@getUsers'));

Route::post('/user/edit', array(
    'before' => 'admin',
    'uses' => 'UsersController@postUserEdit'));

Route::get('/user/profile/{user_id?}', array(
    'before' => 'auth',
    'uses' => 'UsersController@getProfile'));

Route::post('/user/profile-update', array(
    'before' => 'auth|csrf',
    'uses' => 'UsersController@postUpdateProfile'));

Route::post('/user/follow', array(
    'before' => 'auth|csrf',
    'uses' => 'UsersController@postFollow'));

Route::post('/user/unfollow', array(
    'before' => 'auth|csrf',
    'uses' => 'UsersController@postUnfollow'));

Route::post('/user/change-picture', array(
    'before' => 'auth|csrf',
    'uses' => 'UsersController@postProfilePicture'));

Route::get('/user/list', array(
    'before' => 'auth',
    'uses' => 'UsersController@getUsersList'));

Route::get('/user/following/{user_id}', array(
    'before' => 'auth',
    'uses' => 'UsersController@getFollowing'));

Route::get('/user/followers/{user_id}', array(
    'before' => 'auth',
    'uses' => 'UsersController@getFollowers'));

/**
 * Settings routes
 */

Route::get('/settings/gallery', array(
    'before' => 'admin',
    'uses' => 'SettingsController@getGallerySettings'));

Route::post('/settings/gallery', array(
    'before' => 'admin|csrf',
    'uses' => 'SettingsController@postGallerySettings'));

/**
 * Search routes
 */

Route::get('/search', array(
    'before' => 'auth',
    'uses' => 'SearchController@getSearch'));

/**
 * Dashboard Routes
 */
Route::get('/dashboard', array(
    'before' => 'admin',
    'uses' => 'DashboardController@getDashboard'));

Route::get('/', array(
    'before' => 'auth',
    'uses' => 'DashboardController@getHome'));

/**
 * Gallery albums routes
 */
Route::get('/gallery/{user_id?}', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@index'));

Route::post('/gallery/new-album', array(
    'before' => 'auth|csrf',
    'uses' => 'AlbumsController@postCreate'));

Route::get('/gallery/album/{id}', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@show'));

Route::post('/gallery/album/set_cover', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@postSetCover'));

Route::post('/gallery/album/edit', array(
    'before' => 'auth|csrf',
    'uses' => 'AlbumsController@postEdit'));

Route::get('/gallery/album/destroy/{album_id}', array(
    'before' => 'auth',
    'uses' => 'AlbumsController@destroy'));

Route::post('/gallery/album/comment', array(
    'before' => '',
    'uses' => 'AlbumsController@postComment'));

Route::post('/gallery/album/like', array(
    'before' => '',
    'uses' => 'AlbumsController@postLike'));

/**
 * Gallery Photos routes
 */

Route::get('/album/{album_id}/upload', array(
    'before' => 'auth',
    'uses' => 'PhotosController@getUpload'));

Route::post('/album/{album_id}/upload', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postUpload'));

Route::post('photo/destory/{photo_id}', array(
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

Route::post('photo/get-info', array(
    'before' => 'auth',
    'uses' => 'PhotosController@postGetPhotoInfo'));

/**
 * Photo tags routes
 */
Route::post('photo-tag/get', array(
    'before' => 'auth',
    'uses' => 'PhotoTagsController@postGet'));
Route::post('photo-tag/add', array(
    'before' => 'auth',
    'uses' => 'PhotoTagsController@postCreate'));
Route::post('photo-tag/edit', array(
    'before' => 'auth',
    'uses' => 'PhotoTagsController@postEdit'));
Route::post('photo-tag/delete', array(
    'before' => 'auth',
    'uses' => 'PhotoTagsController@postDelete'));

/**
 * Comments routes
 */
Route::post('comment/post', array(
    'before' => 'auth',
    'uses' => 'CommentsController@postComment'));

Route::post('comment/show-comments', array(
    'before' => 'auth',
    'uses' => 'CommentsController@postShowComments'));

/**
 * Likes routes
 */

Route::post('/likes/get', array(
    'before' => 'auth',
    'uses' => 'LikesController@postGetLikes'));

Route::post('/likes/add', array(
    'before' => 'auth',
    'uses' => 'LikesController@postLike'));