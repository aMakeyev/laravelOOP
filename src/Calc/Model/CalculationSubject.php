<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class CalculationSubject extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calculations_subjects';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['calculation_id','constructor_rate_id'];

    protected $fillable = [
        'title', 'num', 'price', 'x', 'y', 'z', 'margin', 'outlay',
        'cost_manufacturing', 'cost_construct', 'cost_assembly',
        'cost_total', 'constructor_rate', 'discount'
    ];

    public $timestamps = true;

    /** EVENTS */
    public static function boot()
    {
        parent::boot();

        self::saving(function (self $model)
        {
            // Объем, на входе мм на выходе куб. м
            $model->volume = $model->x * $model->y * $model->z / pow(1000, 3);
        });

        self::deleting(function (self $model)
        {
            // Удаление елементов
            $model->elements()->delete();
        });
    }

    /** RALATIONS */

    public function calculation()
    {
        return $this->belongsTo('Calc\Model\Calculation', 'calculation_id');
    }

    public function elements()
    {
        return $this->hasMany('Calc\Model\SubjectElement', 'subject_id');
    }

    public function constructorRate()
    {
        return $this->belongsTo('Calc\Model\ConstructorRate', 'constructor_rate_id');
    }

    /** SETTERS */

    public function setXAttribute($value)
    {
        $this->attributes['x'] = (float) $value;
    }

    public function setYAttribute($value)
    {
        $this->attributes['y'] = (float) $value;
    }

    public function setZAttribute($value)
    {
        $this->attributes['z'] = (float) $value;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (float) $value;
    }

    public function setSumAttribute($value)
    {
        $this->attributes['sum'] = (float) $value;
    }

    public function setNumAttribute($value)
    {
        $this->attributes['num'] = (int) $value;
    }

    public function setMarginAttribute($value)
    {
        $this->attributes['margin'] = (float) $value;
    }

    public function setOutlayAttribute($value)
    {
        $this->attributes['outlay'] = (float) $value;
    }

    public function setCostTotalAttribute($value)
    {
        $this->attributes['cost_total'] = (float) $value;
    }

    public function setCostConstructAttribute($value)
    {
        $this->attributes['cost_construct'] = (float) $value;
    }

    public function setCostAssemblyAttribute($value)
    {
        $this->attributes['cost_assembly'] = (float) $value;
    }

    public function setСostManufacturingAttribute($value)
    {
        $this->attributes['cost_manufacturing'] = (float) $value;
    }

    public function setConstructorRateAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            $value = $value['id'];
        }
        else if ($value instanceof ConstructorRate)
        {
            $value = $value->id;
        }

        $this->attributes['constructor_rate_id'] = (int) $value;
    }

    /** GETTERS */

    public function getNumAttribute()
    {
        if ( ! isset($this->attributes['num'])) return null;

        return (int) $this->attributes['num'];
    }
}
