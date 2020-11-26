<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class ConstructorRate extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'constructors_rates';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = ['title','rate'];

    public $timestamps = false;

    /* EVENTS */

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (self $model)
        {
            if ($model->subjects()->count())
            {
                return false;
            }
        });
    }

    /* RELATIONS */

    public function subjects()
    {
        return $this->hasMany('Calc\Model\CalculationSubject', 'constructor_rate_id');
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
