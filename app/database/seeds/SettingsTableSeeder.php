<?php
class SettingsTableSeeder extends Seeder{

    public function run()
    {
        DB::table('settings')->truncate();

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'facebook_app_admins',
            'value' => 'set facebook admins'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'facebook_app_id',
            'value' => 'set facebook app ID'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'facebook_app_secret',
            'value' => 'set facebook app secret'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'max_photo_height',
            'value' => '640'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'max_photo_width',
            'value' => '640'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'thumb_height',
            'value' => '200'
        ));

        Setting::create(array(
            'type' => 'gallery',
            'name' => 'thumb_width',
            'value' => '200'
        ));
    }
} 