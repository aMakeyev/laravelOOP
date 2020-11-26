<?php namespace Calc\Model\Traits;

trait Titleable
{
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = sanitize($value);
    }
}
