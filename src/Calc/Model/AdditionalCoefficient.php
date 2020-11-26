<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class AdditionalCoefficient extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'additional_coefficients';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = ['title','value'];

    public $timestamps = false;

    /* EVENTS */

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (self $model)
        {
            if ($model->calculations()->count())
            {
                return false;
            }
        });
    }

    /* RELATIONS */

    public function calculations()
    {
        return $this->hasMany('Calc\Model\Calculation', 'additional_coefficient_id', 'id');
    }

    /* SETTERS */

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (float) $value;
    }

    /* GETTERS */

    public function getValueAttribute()
    {
        if ( ! isset($this->attributes['value'])) return null;

        return (float) $this->attributes['value'];
    }
}
