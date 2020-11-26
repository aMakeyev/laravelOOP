<?php namespace Calc\Model;

class Income extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'incomes';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [];

    public static $sortable = [];

    protected $appends = ['date'];

    public $timestamps = false;

    /** EVENTS */

    public static function boot()
    {
        self::saving(function (self $model)
        {
            if (empty($model->date)) $model->date = null;
        });
    }

    /** RELATIONS */

    public function calculation()
    {
        return $this->belongsTo('Calc\Model\Calculation');
    }

    /** SETTERS */

    /**
     * Set imcome value
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = (float) $value;
    }

	/**
	 * Set imcome status
	 *
	 * @param $status
	 */
	public function setStatusAttribute($value)
	{
		$this->attributes['status'] = $value;
	}

	public function setCalculationAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            $value = $value['id'];
        }
        else if ($value instanceof Calculation)
        {
            $value = $value->id;
        }

        $this->attributes['calculation_id'] = (int) $value;
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $this->createDate($value);
    }

    /* GETTERS */

    public function getValueAttribute()
    {
        if ( ! isset($this->attributes['value'])) return null;

        return (float) $this->attributes['value'];
    }

	public function getStatusAttribute()

	{
		if ( ! isset($this->attributes['status'])) return null;

		return $this->attributes['status'];
	}
    /**
     * @return null|string
     */
    public function getDateAttribute()
    {
        if ( ! $this->attributes['date']) return null;

        return $this->asDateTime($this->attributes['date'])->format(self::DATE_FORMAT);
    }
}
