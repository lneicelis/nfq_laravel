<?php

class SearchController extends \BaseController {

    public function __construct()
    {
        Breadcrumbs::addCrumb('Home', URL::action('DashboardController@getHome'));
        Breadcrumbs::addCrumb('Search', URL::action('SearchController@getSearch'));
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        $needle = Input::get('search');
        $photos = null;
        if(preg_match('/#/', $needle))
        {
            $photo_ids = $this->searchTags($needle);

            $photos = Photo::find($photo_ids);
        }

        if(empty($photos))
        {
            $alerts[] = array(
                'type' => 'info',
                'title' => 'Info',
                'message' => 'Your search query did not return any results.');
        }
        //var_dump(DB::getQueryLog());
        //var_dump($photos);
        return View::make('admin.search.photos', array(
            'alerts' => @$alerts,
            'photos' => $photos,
            'needle' => $needle
        ));
    }

    /**
     * @param $tag
     * @return array
     */
    protected function searchTags($tag)
    {
        $needle = str_replace('#', '', $tag);

        foreach(PhotoTag::search($needle) as $tag)
        {
            $photo_ids[] = $tag->photo_id;
        }
        return $photo_ids;
    }
} 