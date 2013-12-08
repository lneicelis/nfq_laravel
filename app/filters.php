<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    Breadcrumbs::addCrumb('Home', URL::action('DashboardController@getHome'));

    View::composer('admin.layouts.master', function($view)
    {
        $thumb = UserInfo::findById(Sentry::getUser()->id)->picture;
        $view->with('thumb', $thumb);
    });

    View::composer('admin.components.sidebar', function($view)
    {
        if(Sentry::getUser()->hasAccess('admin'))
        {
            $meniu[] = array('icon' => 'icon-dashboard',    'title' => 'Dashboard',     'url' => URL::action('DashboardController@getDashboard'));
            $meniu[] = array('icon' => 'icon-group',        'title' => 'Users',         'url' => URL::action('UsersController@getUsers'));
            $meniu[] = array('icon' => 'icon-cogs',        'title' => 'Settings',         'url' => URL::action('SettingsController@getGallerySettings'));
        }

        if(Sentry::getUser()->hasAccess('user'))
        {
            $meniu[] = array('icon' => 'icon-user',         'title' => 'Profile',       'url' => URL::action('UsersController@getProfile', array('user_id' => Sentry::getUser()->id)));
            $meniu[] = array('icon' => 'icon-picture',      'title' => 'Gallery',       'url' => URL::action('AlbumsController@index'));
        }

        $view->with('meniu', $meniu);
    });

});


App::after(function($request, $response)
{

});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    $user = Sentry::getUser();
    if(empty($user)){
        return Redirect::to('user/login');
    }
});

Route::filter('admin', function()
{
    $user = Sentry::getUser();
    if(empty($user)){
        return Redirect::to('user/login');
    }
    if(!$user->hasAccess('admin'))
    {
        App::abort(404, 'You do not have access to this page!');
    }
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});