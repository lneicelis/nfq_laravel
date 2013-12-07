<?php
/**
 * Created by PhpStorm.
 * User: Luko
 * Date: 13.10.25
 * Time: 14.07
 */

class Comment extends Eloquent {

    protected $table = 'comments';

    protected $fillable = array('type', 'obj_id', 'user_id', 'comment');

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