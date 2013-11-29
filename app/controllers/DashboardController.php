<?php

class DashboardController extends \BaseController
{
    public function __construct(){
        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
    }

    public function getDashboard()
    {
        $albums = Album::all();
        $photos = Photo::all();
        $photo_tags = PhotoTag::all();

        $users = Sentry::all();
        $admins = Sentry::findAllUsersWithAccess(array('admin'));

        return View::make('admin.dashboard.dashboard', array(
            'albums' => $albums,
            'photo_tags' => $photo_tags,
            'photos' => $photos,
            'users' => $users,
            'admins' => $admins
        ));
    }

    public function getHome()
    {
        $start = strtotime('last week');

        $trending_albums = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.created_at', '>', $start)
            ->select('albums.id', 'albums.user_id', 'albums.title', 'albums.description', 'albums.no_photos', 'albums.no_comments', 'albums.no_likes', 'photos.file_name')
            ->orderBy('no_comments', 'DESC')->take(5)->get();

        $trending_photos = DB::table('photos')
            ->where('photos.created_at', '>', $start)
            ->orderBy('no_comments', 'DESC')->take(5)->get();


        $default_cover = 'default.jpg';

        return View::make('admin.dashboard.home', array(
            'trending_albums' => $trending_albums,
            'trending_photos' => $trending_photos,
            'default_cover' => $default_cover
        ));
    }
} 