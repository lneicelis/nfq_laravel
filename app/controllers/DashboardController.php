<?php

class DashboardController extends \BaseController
{

    /**
     * @return mixed
     */
    public function getDashboard()
    {
        /**
         * select * from `albums`
         */
        $response['albums'] = Album::all();
        /**
         * select * from `photos`
         */
        $response['photos'] = Photo::all();
        /**
         * select * from `photo_tags`
         */
        $response['photo_tags'] = PhotoTag::all();
        /**
         * select * from `users`
         */
        $response['users'] = Sentry::all();
        /**
         *
         */
        $response['comments'] = Comment::all();
        /**
         *
         */
        $response['likes'] = Comment::all();

        $response['admins'] = Sentry::findAllUsersWithAccess(array('admin'));

        Breadcrumbs::addCrumb('Dashboard');

        return View::make('admin.dashboard.dashboard', $response);
    }

    /**
     * @return mixed
     */
    public function getHome()
    {
        $start = strtotime('last week');
        /**
         * select `albums`.`id`, `albums`.`user_id`, `albums`.`title`, `albums`.`description`, `albums`.`no_photos`, `albums`.`no_comments`, `albums`.`no_likes`, `photos`.`file_name` from `albums` left join `photos` on `albums`.`cover_photo` = `photos`.`id` where `albums`.`created_at` > ? order by `no_comments` desc limit 5
         */
        $response['trending_albums'] = DB::table('albums')
            ->leftJoin('photos', 'albums.cover_photo', '=', 'photos.id')
            ->where('albums.created_at', '>', $start)
            ->select('albums.id', 'albums.user_id', 'albums.title', 'albums.description', 'albums.no_photos', 'albums.no_comments', 'albums.no_likes', 'photos.file_name')
            ->orderBy('no_likes', 'DESC')->take(5)->get();
        /**
         * select * from `photos` where `photos`.`created_at` > ? order by `no_comments` desc limit 5
         */
        $response['trending_photos'] = DB::table('photos')
            ->where('photos.created_at', '>', $start)
            ->orderBy('no_comments', 'DESC')->take(5)->get();


        $response['default_cover'] = 'default.jpg';

        return View::make('admin.dashboard.home', $response);
    }
} 