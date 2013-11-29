<?php

class UserFollow extends Eloquent{

    protected $table = 'users_follow';

    protected $fillable = array('following_id', 'follower_id');

} 