<?php

class UserInfo extends Eloquent{

    protected $table = 'users_info';

    protected $fillable = array('user_id', 'age', 'skype', 'website');

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function scopeFindById($query, $id)
    {
        return $query->where('user_id', '=', $id)->first();
    }
} 