<?php
class GroupTableSeeder extends Seeder{

    public function run()
    {
        DB::table('groups')->truncate();

        Sentry::createGroup(array(
            'name'        => 'Administrator',
            'permissions' => array(
                'admin' => 1,
                'moderator' => 1,
                'user' => 1,
            ),
        ));

        Sentry::createGroup(array(
            'name'        => 'Moderator',
            'permissions' => array(
                'admin' => 0,
                'moderator' => 1,
                'user' => 1,
            ),
        ));

        Sentry::createGroup(array(
            'name'        => 'User',
            'permissions' => array(
                'admin' => 0,
                'moderator' => 0,
                'user' => 1,
            ),
        ));
    }
} 