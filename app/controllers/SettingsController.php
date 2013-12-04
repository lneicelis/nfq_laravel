<?php

class SettingsController extends \BaseController{

    public function __construct()
    {
        Breadcrumbs::addCrumb('Home', URL::action('DashboardController@getHome'));
        Breadcrumbs::addCrumb('Settings', URL::action('SettingsController@getGallerySettings'));
    }
    public function getGallerySettings()
    {
        $settings = Setting::getByType('gallery')->get();

        foreach($settings as $row)
        {
            $gallery_settings[$row->name] = $row->value;
        }


        return View::make('admin.settings.gallery-settings',array(
            'settings' => $gallery_settings
        ));
    }

    public function postGallerySettings()
    {

        $validator = Validator::make(
            Input::get(),
            array(
                'type' => 'required',
                'name' => 'required',
                'value' => 'required',
            )
        );
        if(!$validator->fails())
        {
            if(!$validator->fails())
            {
                $type = Input::get('type');
                $name = Input::get('name');

                Setting::where('type', '=', $type)->where('name', '=', $name)
                    ->update(array('value' => Input::get('value')));;

                return Response::make('ok', 200);
            }else{
                return Response::make('failed', 404);
            }
        }
    }
} 