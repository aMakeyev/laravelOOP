<?php namespace Calc\Model\Traits;

trait Statusable
{
    public function setStatusAtrribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    public function getStatusAttribute()
    {
        if ( ! isset($this->attributes['status'])) return null;

        return (int) $this->attributes['status'];
    }

    public function scopeStatus($q, $status)
    {
        if ($status = (int) $status)
        {
            $q->where('status', $status);
        }

        return $q;
    }
}
