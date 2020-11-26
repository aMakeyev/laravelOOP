<?php namespace Calc\Model;

use Auth;
use Calc\Model\Traits\Descriptionable;
use Calc\Model\Traits\Statusable;

/**
 * Class ShatersOrder
 * @package Calc\Model
 *
 * @property $id
 * @property $calculation_id
 * @property $contractor_id
 * @property $subject_id
 * @property $description
 * @property $status
 * @property $called_at
 * @property $next_call_at
 * @property $cost
 * @property $created_at
 * @property $updated_at
 * @property $constructor_name
 * @property $designer_name
 * @property $installer_name
 *
 * @property Client $client
 */
class ShatersOrder extends BaseModel
{
    use Statusable, Descriptionable;

    protected $editableStatuses = [1, 2, 3, 4, 5];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shaters_orders';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [
        'contractor', 'status', 'called_at', 'next_call_at', 'description',
        'constructor_name', 'designer_name', 'installer_name',
    ];

    public static $sortable = [
        'created_at', 'updated_at', 'contractor_id', 'calculation_id',
        'called_at', 'next_call_at', 'status', 'cost',
    ];

    protected $appends = [
        'called_at', 'next_call_at',
    ];

    /**
     * The class name to be used in polymorphic relations.
     *
     * @var string
     */
    protected $morphClass = 'ShatersOrder';

    /** EVENTS */

    public static function boot()
    {
        self::saving(function (self $model)
        {
            if (empty($model->called_at)) $model->called_at = null;
            if (empty($model->next_call_at)) $model->next_call_at = null;
        });

        self::deleting(function (self $model)
        {
            /** @var ConstructorOutlay $outlay */
            foreach ($model->constructorOutlay as $outlay)
            {
                $outlay->delete();
            }

            /** @var ContractorOutlay $outlay */
            foreach ($model->contractorOutlay as $outlay)
            {
                $outlay->delete();
            }

            /** @var File $file */
            foreach ($model->files as $file)
            {
                $file->delete();
            }
        });
    }

    /* RELATIONS */

    public function contractor()
    {
        return $this->belongsTo('Calc\Model\Contractor');
    }

    public function subject()
    {
        return $this->belongsTo('Calc\Model\ShatersCalculationSubject');
    }

    public function calculation()
    {
        return $this->belongsTo('Calc\Model\ShatersCalculation', 'calculation_id');
    }

    public function files()
    {
        return $this->morphMany('Calc\Model\File', 'fileable');
    }

    public function contractorOutlay()
    {
        return $this->hasMany('Calc\Model\ShatersContractorOutlay');
    }

    public function constructorOutlay()
    {
        return $this->hasMany('Calc\Model\ShatersConstructorOutlay');
    }

    public function canEdit()
    {
        if ( ! $this->exists)
        {
            return false;
        }

        if ( ! in_array($this->status, $this->editableStatuses))
        {
            return false;
        }

        return true;
    }

    /** SETTERS */

    /**
     * Set order cost
     *
     * @param $value
     */
    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = (float) $value;
    }

    public function setConstructorNameAttribute($value)
    {
        $this->attributes['constructor_name'] = trim((string) $value);
    }

    public function setDesignerNameAttribute($value)
    {
        $this->attributes['designer_name'] = trim((string) $value);
    }

    public function setInstallerNameAttribute($value)
    {
        $this->attributes['installer_name'] = trim((string) $value);
    }

    public function setSubjectAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            $value = $value['id'];
        }
        else
        {
            if ($value instanceof ShatersCalculationSubject)
            {
                $value = $value->id;
            }
        }

        $this->attributes['subject_id'] = (int) $value;
    }

    public function setCalculationAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            $value = $value['id'];
        }
        else
        {
            if ($value instanceof ShatersCalculation)
            {
                $value = $value->id;
            }
        }

        $this->attributes['calculation_id'] = (int) $value;
    }

    public function setContractorAttribute($value)
    {
        if (is_array($value) && isset($value['id']))
        {
            $value = $value['id'];
        }
        else
        {
            if ($value instanceof Contractor)
            {
                $value = $value->id;
            }
        }

        $this->attributes['contractor_id'] = (int) $value;
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setCalledAtAttribute($value)
    {
        $this->attributes['called_at'] = $this->createDate($value);
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setNextCallAtAttribute($value)
    {
        $this->attributes['next_call_at'] = $this->createDate($value);
    }

    /**
     * @return null|string
     */
    public function getCalledAtAttribute()
    {
        if ( ! isset($this->attributes['called_at'])) return null;

        return $this->asDateTime($this->attributes['called_at'])->format(self::DATE_FORMAT);
    }

    /**
     * @return null|string
     */
    public function getNextCallAtAttribute()
    {
        if ( ! isset($this->attributes['next_call_at'])) return null;

        return $this->asDateTime($this->attributes['next_call_at'])->format(self::DATE_FORMAT);
    }

    /** SCOPES */

    public function scopeOwn($q)
    {
        if (Auth::user()->isAdmin())
        {
            return $q;
        }

        return $q;
    }
}
