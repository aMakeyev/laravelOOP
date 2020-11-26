<?php namespace Calc\Model\Traits;

trait Descriptionable
{
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = sanitize($value);
    }
}
