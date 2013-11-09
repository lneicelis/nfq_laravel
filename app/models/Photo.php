<?php
/**
 * Created by PhpStorm.
 * User: Luko
 * Date: 13.10.25
 * Time: 10.55
 */

class photo extends Eloquent {

    protected $table = 'photos';

    protected $fillable = array('album_id', 'file_name', 'description');

    public function album()
    {
        return $this->belongsTo('Album');
    }
} 