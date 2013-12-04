<?php

class UserFollow extends Eloquent{

    protected $table = 'users_follow';

    protected $fillable = array('following_id', 'follower_id');

    public function scopeFindByIds($query, $following, $follower)
    {
        return $query->where('following_id', '=', $following)->where('follower_id', '=', $follower)->first();
    }
} 