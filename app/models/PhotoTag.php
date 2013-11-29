<?php

class PhotoTag extends Eloquent{

    protected $table = 'photo_tags';

    protected $fillable = array('photo_id', 'title', 'description', 'x', 'y');

    public function photo()
    {
        return $this->belongsTo('Photo');
    }

    public function scopeSearch($query, $tag)
    {
        $tag = '%' . $tag . '%';
        return $query->where('title', '>', $tag)->take(5)->get();
    }
} 