<?php

class DashboardController extends \BaseController
{
    public function __construct(){
        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
    }

    public function getDashboard()
    {
        $response['albums'] = Album::all();
        $response['photos'] = Photo::all();
        $response['photo_tags'] = PhotoTag::all();

        $response['users'] = Sentry::all();
        $response['admins'] = Sentry::findAllUsersWithAccess(array('admin'));

        return View::make('admin.dashboard.dashboard', $response);
    }

    public function getHome()
    {
        $start = strtotime('last week');

        $response['trending_albums'] = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.created_at', '>', $start)
            ->select('albums.id', 'albums.user_id', 'albums.title', 'albums.description', 'albums.no_photos', 'albums.no_comments', 'albums.no_likes', 'photos.file_name')
            ->orderBy('no_comments', 'DESC')->take(5)->get();

        $response['trending_photos'] = DB::table('photos')
            ->where('photos.created_at', '>', $start)
            ->orderBy('no_comments', 'DESC')->take(5)->get();


        $response['default_cover'] = 'default.jpg';

        return View::make('admin.dashboard.home', $response);
    }
} 