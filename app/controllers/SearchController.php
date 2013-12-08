<?php

class SearchController extends \BaseController
{

    public function __construct()
    {
        Breadcrumbs::addCrumb('Search', URL::action('SearchController@getSearch'));
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        $needle = Input::get('search');
        $response['needle'] = $needle;
        $response['photos'] = null;
        $response['users'] = null;
        $response['default_cover'] = 'default.jpg';

        if (preg_match('/#/', $needle)) {
            $photo_ids = $this->searchTags($needle);
            if (!empty($photo_ids)) {
                $response['photos'] = $photos = Photo::whereIn('id', $photo_ids)->paginate(15);
            }
        } else {
            $response['users'] = $users = $this->searchUsers($needle);
            $response['albums'] = $albums = $this->searchAlbums($needle);
        }

        if (empty($photos) && empty($users) && empty($albums)) {
            $response['alerts'][] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'Your search query did not return any results.');
        }

        return View::make('admin.search.main', $response);
    }

    /**
     * @param $tag
     * @return array
     */
    protected function searchTags($tag)
    {
        $needle = str_replace('#', '', $tag);
        $photo_ids = array();
        /**
         * select * from `photo_tags` where `title` like ? limit 15
         */
        foreach (PhotoTag::search($needle) as $tag) {
            $photo_ids[] = $tag->photo_id;
        }
        return $photo_ids;
    }

    /**
     * @param $needle
     * @return mixed
     */
    protected function searchUsers($needle)
    {
        $needle = '%' . $needle . '%';
        /**
         * select count(*) as aggregate from `users` inner join `users_info` on `users_info`.`user_id` = `users`.`id`
         * where CONCAT(users.first_name, " ", users.last_name) like ?
         */
        $users = DB::table('users')
            ->whereRaw('CONCAT(users.first_name, " ", users.last_name) like ?', array($needle))
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.activated_at', 'users.last_login',
                'users_info.age', 'users_info.skype', 'users_info.website', 'users_info.picture')->paginate(30);
        return $users;
    }
    protected function searchAlbums($needle)
    {
        $needle = '%' . $needle . '%';

        $albums = DB::table('albums')->where('title', 'like', $needle)->paginate(15);

        return $albums;
    }
} 