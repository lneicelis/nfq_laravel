<?php

class User extends Cartalyst\Sentry\Users\Eloquent\User {

    public function info()
    {
        return $this->hasOne('UserInfo');
    }

}