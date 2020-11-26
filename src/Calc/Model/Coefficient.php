<?php namespace Calc\Model;

class Coefficient extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coefficients';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = ['title','range_start','range_end','value'];

    public $timestamps = false;

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (float) $value;
    }

    public function getValueAttribute()
    {
        if ( ! isset($this->attributes['value'])) return null;

        return (float) $this->attributes['value'];
    }
}
