<?php

class PhotoTag extends Eloquent{

    protected $table = 'photo_tags';

    protected $fillable = array('photo_id', 'title', 'description', 'x', 'y');

    public function photo()
    {
        return $this->belongsTo('Photo');
    }
} 