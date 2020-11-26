<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class SubjectElement extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subjects_elements';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['subject_id','part_id'];

    protected $fillable = [
        'title', 'price', 'sum', 'total', 'character', 'x', 'y', 'z', 'part', 'unit_price'
    ];

    public $timestamps = false;

    /** EVENTS */

    public static function boot()
    {
        parent::boot();

        self::saving(function (self $model)
        {
            // Площадь, кв. мм переводим в кв. м
            $model->area = $model->x * $model->y / pow(1000, 3);
            // Объем, куб. мм переводим в куб. м
            $model->volume = $model->x * $model->y * $model->z / pow(1000, 3);
        });
    }

    /** RELATIONS */

    public function subject()
    {
        return $this->belongsTo('Calc\Model\CalculationSubject', 'subject_id');
    }

    public function element()
    {
        return $this->belongsTo('Calc\Model\Element', 'character');
    }

    public function part()
    {
        return $this->hasOne('Calc\Model\Part', 'id', 'part_id');
    }

    /** GETTERS */

    /** SETTERS */

    /**
     * Set subject element X
     *
     * @param $value
     */
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

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = (float) $value;
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (int) $value;
    }

    public function setCharacterAttribute($value)
    {
        $this->attributes['character'] = (int) $value;
    }

    public function setPartAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            if (isset($value['unit_price']))
            {
                $this->unit_price = (float) $value['unit_price'];
            }

            $value = $value['id'];
        }
        else if ($value instanceof Part)
        {
            $this->unit_price = $value->unit_price;
            $value = $value->id;
        }

        $this->attributes['part_id'] = (int) $value;
    }
}
