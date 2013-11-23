<?php

class DashboardController extends \BaseController
{
    public function __construct(){
        Breadcrumbs::addCrumb('Home', URL::action('AlbumsController@index'));
        Breadcrumbs::addCrumb('Dashboard');
    }

    public function getIndex()
    {
        $albums = Album::all();
        $photos = Photo::all();
        $photo_tags = PhotoTag::all();

        $users = Sentry::all();

        return View::make('admin.dashboard.dashboard', array(
            'albums' => $albums,
            'photo_tags' => $photo_tags,
            'photos' => $photos,
            'users' => $users));
    }
} 