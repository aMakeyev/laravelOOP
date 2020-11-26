<?php namespace Calc\Model;

use Auth;
use Calc\Model\Traits\Statusable;
use Calc\Model\Traits\Titleable;
use Calc\Model\Traits\Descriptionable;

/**
 * Class Calculation
 * @package Calc\Model
 * 
 * @property $title
 * @property $status
 * @property $outlay
 * @property $margin
 * @property $discount
 * @property $cost_assembly
 * @property $cost_construct
 * @property $cost_manufacturing
 * @property $cost_total
 * @property $delivery
 * @property $install
 * @property $additional_coefficient
 * @property $client
 * @property $install_address
 * @property $delivery_address
 * @property $install_at
 * @property $delivery_at
 * @property $description
 * @property $pseudo_discount_percent
 * @property $pseudo_discount_meter
 * @property $climb_price
 * @property $make_at
 * @property $parent_id
 * @property $child_id
 * @property $contract_at
 * @property $addagree_price
 * @property $cost_final
 */
class Calculation extends BaseModel
{
    use Statusable, Titleable, Descriptionable;

    /**
     * Оплачен аванс
     */
    const MAKE_ORDERS_STATUS = 6;

    /**
     * Статусы при которых разрешено редактировать расчет
     *
     * @var array
     */
    protected $editableStatuses = [
        1, 2, 3, 4, 5,
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calculations';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [
        'title', 'status', 'outlay', 'margin', 'discount', 'cost_assembly', 'cost_construct',
        'cost_manufacturing', 'cost_total', 'delivery', 'install', 'additional_coefficient',
        'client', 'install_address', 'delivery_address', 'install_at', 'delivery_at',
        'description', 'pseudo_discount_percent', 'pseudo_discount_meter', 'climb_price',
        'make_at','parent_id','child_id', 'contract_at','addagree_price','cost_final'
    ];

    public static $sortable = [
        'created_at', 'title', 'cost_total', 'status',
        'users.last_name', 'clients.last_name',
    ];

    protected $dates = [
        'delivery_at', 'install_at', 'make_at'
    ];

    protected $appends = [
        'status_text',
    ];

    /**
     * The class name to be used in polymorphic relations.
     *
     * @var string
     */
    protected $morphClass = 'Calculation';

    /** EVENTS */

    public static function boot()
    {
        parent::boot();

        self::saving(function (self $model) {
            if (empty($model->delivery_at)) $model->delivery_at = null;
            if (empty($model->install_at)) $model->install_at = null;
            if (empty($model->make_at)) $model->make_at = null;
        });

        self::creating(function (self $model) {
            if (!Auth::check()) {
                return false;
            }

            $model->user_id = Auth::user()->id;
        });

        // Удаление расчета
        self::deleting(function (self $model) {
            if ($model->orders()->count()) {
                return false;
            }

            /** @var CalculationSubject $subject */
            foreach ($model->subjects as $subject) {
                // Удаляем элементы предмета
                $subject->elements()->delete();
                // Удаляем предмет
                $subject->delete();
            }

            /** @var File $file */
            foreach ($model->files as $file) {
                // Удаляем файлы
                $file->delete();
            }

            /** @var Income $income */
            foreach ($model->incomes as $income) {
                // Удаляем оплату
                $income->delete();
            }
        });
    }

    /** RELATIONS */

    /**
     * Связанный менеджер
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo('Calc\Model\User', 'user_id');
    }

    /**
     * Связанный клиент
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('Calc\Model\Client', 'client_id');
    }

    /**
     * Связанные заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('Calc\Model\Order', 'calculation_id');
    }

    /**
     * Связанный дополнительный коэффициент
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function additionalCoefficient()
    {
        return $this->belongsTo('Calc\Model\AdditionalCoefficient', 'additional_coefficient_id');
    }

    /**
     * Связанные предметы мебели
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects()
    {
        return $this->hasMany('Calc\Model\CalculationSubject', 'calculation_id');
    }

    /**
     * Связанные поступления от клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomes()
    {
        return $this->hasMany('Calc\Model\Income', 'calculation_id');
    }

    /**
     * Связанные файлы
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany('Calc\Model\File', 'fileable');
    }

    /** HELPERS METHODS  */

    public function canEdit()
    {
        if (!$this->exists) {
            return false;
        }

        if (!in_array($this->status, $this->editableStatuses)) {
            return false;
        }

        return true;
    }

    /** SETTERS */

    /**
     * @param $value
     */
    public function setDeliveryAddressAttribute($value)
    {
        $this->attributes['delivery_address'] = sanitize($value);
    }

    /**
     * @param $value
     */
    public function setInstallAddressAttribute($value)
    {
        $this->attributes['install_address'] = sanitize($value);
    }

    /**
     * @param $value
     */
    public function setClientAttribute($value)
    {
        if (is_array($value) && isset($value['id'])) {
            $value = $value['id'];
        } else {
            if ($value instanceof Client) {
                $value = $value->id;
            }
        }

        $this->attributes['client_id'] = (int)$value;
    }

    /**
     * @param $value
     */
    public function setAdditionalCoefficientAttribute($value)
    {
        if (is_array($value) && isset($value['id'])) {
            $value = $value['id'];
        } else {
            if ($value instanceof AdditionalCoefficient) {
                $value = $value->id;
            }
        }

        $this->attributes['additional_coefficient_id'] = (int)$value;
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function getInstallAtAttribute($value)
    {
        if (!$value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function getDeliveryAtAttribute($value)
    {
        if (!$value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function getMakeAtAttribute($value)
    {
        if (!$value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    /**
     * @param $value
     */
    public function setInstallAttribute($value)
    {
        $this->attributes['install'] = (float)$value;
    }

    /**
     * @param $value
     */
    public function setMakeAttribute($value)
    {
        $this->attributes['make'] = (float)$value;
    }

    /**
     * @param $value
     */
    public function setCostTotalAttribute($value)
    {
        $this->attributes['cost_total'] = (float)$value;
    }

    /**
     * @param $value
     */
    public function setCostConstructAttribute($value)
    {
        $this->attributes['cost_construct'] = (float)$value;
    }

    /**
     * @param $value
     */
    public function setCostAssemblyAttribute($value)
    {
        $this->attributes['cost_assembly'] = (float)$value;
    }

    /**
     * @param $value
     */
    public function setСostManufacturingAttribute($value)
    {
        $this->attributes['cost_manufacturing'] = (float)$value;
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setInstallAtAttribute($value)
    {
        $this->attributes['install_at'] = $this->createDate($value);
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setDeliveryAtAttribute($value)
    {
        $this->attributes['delivery_at'] = $this->createDate($value);
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    public function setMakeAtAttribute($value)
    {
        $this->attributes['make_at'] = $this->createDate($value);
    }
	/**
	 * @param string $value
	 *
	 * @return null|string
	 */
	public function setContractAtAttribute($value)
	{
		$this->attributes['contract_at'] = $this->createDate($value);
	}

    /** GETTERS */

    public function getDeliveryAttribute()
    {
        if (!isset($this->attributes['delivery'])) return null;

        return (float)$this->attributes['delivery'];
    }

    public function getInstallAttribute()
    {
        if (!isset($this->attributes['install'])) return null;

        return (float)$this->attributes['install'];
    }

    public function getMakeAttribute()
    {
        if (!isset($this->attributes['make'])) return null;

        return (float)$this->attributes['make'];
    }

    /**
     * @return null|string
     */
    public function getCreatedAtAttribute()
    {
        if (!$this->attributes['created_at']) return null;

        return $this->asDateTime($this->attributes['created_at'])->format(self::DATE_FORMAT);
    }

    /**
     * @return null|string
     */
    public function getUpdatedAtAttribute()
    {
        if (!$this->attributes['updated_at']) return null;

        return $this->asDateTime($this->attributes['updated_at'])->format(self::DATE_FORMAT);
    }

	/**
	 * @return null|string
	 */
	public function getContractAtAttribute()
	{
		if (!$this->attributes['contract_at']) return null;

		return $this->asDateTime($this->attributes['contract_at'])->format(self::DATE_FORMAT);
	}

    /* SCOPES */

    /**
     * @param     $q
     * @param int $manager
     *
     * @return mixed
     */
    public function scopeManager($q, $manager)
    {
        if (!$manager = (int)$manager) {
            return $q;
        }

        return $q->where('user_id', $manager);
    }

    /**
     * @param $q
     *
     * @return mixed
     */
    public function scopeOwn($q)
    {
        if (Auth::user()->isAdmin() || Auth::user()->isHeadManager()) {
            return $q;
        }

        return $q->where('user_id', Auth::user()->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param string $term
     *
     * @return mixed
     */
    public function scopeSearch($q, $term)
    {
        if ($term = sanitize((string)$term)) {
            $q->whereHas('client', function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    if (strpos($term, ' ')) {
                        list($last_name, $first_name) = explode(' ', $term, 2);
                    } else {
                        $first_name = $term;
                        $last_name = $term;
                    }
                    $q->where('first_name', 'LIKE', "{$first_name}%");
                    if (strpos($term, ' ')) {
                        $q->where('last_name', 'LIKE', "{$last_name}%");
                    } else {
                        $q->orWhere('last_name', 'LIKE', "{$last_name}%");
                    }

                    $q->orWhere('email', 'LIKE', "{$term}%");
                    $q->orWhere('phone', 'LIKE', "{$term}%");
                });
            });
        }

        if((int)$term > 0) {
            $q->orWhere('id', '=', (int)$term);
        }

        return $q;
    }

    public function getStatusTextAttribute()
    {
        static $statuses;

        if (!$statuses) $statuses = \Config::get('calc::calculation/statuses');

        return isset($this->attributes['status'], $statuses[$this->attributes['status']])
            ? $statuses[$this->attributes['status']] : '';
    }
	public function getPseudoDiscountPercentTextAttribute()
	{
		static $pseudo_discount_percent;

		if (!$pseudo_discount_percent) $pseudo_discount_percent = \Config::get('calc::calculation/discounts');

		return isset($this->attributes['pseudo_discount_percent'], $pseudo_discount_percent[$this->attributes['pseudo_discount_percent']])
			? $pseudo_discount_percent[$this->attributes['pseudo_discount_percent']] : '';
	}
}
