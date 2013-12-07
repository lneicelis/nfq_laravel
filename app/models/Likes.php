<?php

class Like extends Eloquent {

    protected $table = 'likes';

    protected $fillable = array('type', 'obj_id', 'user_id');

    /**
     * @return mixed
     */
    public function photo()
    {
        return $this->belongsTo('Photo');
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

} 