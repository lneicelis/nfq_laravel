<?php

class Setting extends Eloquent{

    protected $table = 'settings';

    protected $fillable = array('type', 'name', 'value');

    public function scopeGetByType($query, $type)
    {
        return $query->where('type', '=', $type);
    }

    public function scopeFindSettings($query, $type, $name)
    {
        $settings = $query->where('type', '=', $type)->where('name', '=', $name)->first();
        return $settings->value;
    }

} 