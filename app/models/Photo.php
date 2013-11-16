<?php

class photo extends Eloquent {

    protected $table = 'photos';

    protected $fillable = array('album_id', 'file_name', 'description');

    public function album()
    {
        return $this->belongsTo('Album');
    }
} 