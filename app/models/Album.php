<?php
/**
 * Created by PhpStorm.
 * User: Luko
 * Date: 13.10.25
 * Time: 14.07
 */

class Album extends Eloquent {

    protected $table = 'albums';

    protected $fillable = array('user_id', 'title', 'description', 'cover_photo', 'no_photos');

    public function photos()
    {
        return $this->hasMany('Photo', 'album_id');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function userInfo()
    {
        return $this->belongsTo('UserInfo');
    }
} 