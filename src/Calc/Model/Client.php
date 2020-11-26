<?php namespace Calc\Model;

use Auth;
use Str;
use Calc\Model\Traits\Statusable;
use Calc\Presenters\PresentableTrait;

/**
 * Class Client
 * @package Calc\Model
 *
 * @property $id
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $phone
 * @property $description
 * @property $handled_at
 * @property $status
 * @property $type
 * @property $created_at
 * @property $updated_at
 * @property $last_contact_at
 * @property $next_contact_at
 * @property $created_by
 * @property $subscribe
 * @property $delivery_address
 * @property $second_name
 * @property $birthday
 * @property $passport_series
 * @property $passport_number
 * @property $passport_issued_by
 * @property $passport_issued_code
 * @property $passport_issued_date
 * @property $passport_address
 */


class Client extends BaseModel
{
    use PresentableTrait, Statusable;

    protected $presenter = 'ClientPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static $sortable = [
        'id', 'last_name', 'first_name', 'email', 'phone', 'status', 'type',
        'handled_at', 'created_at', 'users.last_name', 'next_contact_at', 'subscribe',
        'delivery_address', 'second_name', 'birthday', 'passport_series', 'passport_number',
        'passport_issued_by', 'passport_issued_code', 'passport_issued_date', 'passport_address', 'legal_name', 'inn', 'kpp', 'legal_address', 'mail_address', 'bank_name', 'settlement_account', 'correspondent_account', 'bik', 'okpo', 'okato', 'oktmo', 'okved', 'ogrn', 'legal_phone', 'legal_email', 'director_name',
    ];

    protected $fillable = [
        'last_name', 'first_name', 'email', 'phone', 'description', 'status',
        'type', 'last_contact_at', 'next_contact_at', 'handled_at', 'created_by', 'subscribe',
        'delivery_address', 'second_name', 'birthday', 'passport_series', 'passport_number',
        'passport_issued_by', 'passport_issued_code', 'passport_issued_date', 'passport_address', 'legal_name', 'inn', 'kpp', 'legal_address', 'mail_address', 'bank_name', 'settlement_account', 'correspondent_account', 'bik', 'okpo', 'okato', 'oktmo', 'okved', 'ogrn', 'legal_phone', 'legal_email', 'director_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'handled_at'
    ];

    protected $appends = ['date','type_text'];

	protected $morphClass = 'Client';

	/* EVENTS */

    public static function boot()
    {
        parent::boot();

        self::creating(function (self $model)
        {
            if ( ! Auth::check())
            {
                return false;
            }

            $model->created_by = user()->id;
        });

        self::saving(function (self $model)
        {
            // Первая буква в имени и фамилии заглавная
            $model->first_name = Str::title($model->first_name);
            $model->last_name = Str::title($model->last_name);
            $model->second_name = Str::title($model->second_name);

            if (empty($model->last_contact_at)) $model->last_contact_at = null;
            if (empty($model->next_contact_at)) $model->next_contact_at = null;
            if (empty($model->birthday)) $model->birthday = null;
            if (empty($model->passport_issued_date)) $model->passport_issued_date = null;
        });

        // Удаление клиента
        self::deleting(function (self $model)
        {
            // Если с клиентом связаны расчеты не удаляем его
            if ($model->calculations()->count())
            {
                return false;
            }
        });
    }

    /** RELATIONS */

    /**
     * Метод для получения всех расчетов клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calculations()
    {
        return $this->hasMany('Calc\Model\Calculation', 'client_id');
    }

    /**
     * Метод для получения всех заказов клиента через расчеты
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough('Calc\Model\Order', 'Calc\Model\Calculation', 'client_id', 'calculation_id');
    }

    /**
     * Метод для получение экземпляра менеджера добавившего клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('Calc\Model\User', 'created_by');
    }

    /**
     * Метод для получение облегченного экземпляра менеджера добавившего клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo('Calc\Model\SimpleManager', 'created_by');
    }

    /* GETTERS */

    /**
     * Получение форматированной даты
     *
     * @return null|string
     */
    public function getDateAttribute()
    {
        if ( ! isset($this->attributes['created_at'])) return null;

        return $this->created_at->format(self::DATE_FORMAT);
    }

    /**
     * Получение форматированной даты последнего звонка
     *
     * @return null|string
     */
    public function getLastContactAtAttribute($value)
    {
        if ( ! $value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    /**
     * Получение форматированной даты следующего звонка
     *
     * @return null|string
     */
    public function getNextContactAtAttribute($value)
    {
        if ( ! $value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    public function getBirthdayAttribute($value)
    {
        if (!$value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    public function getPassportIssuedDateAttribute($value)
    {
        if (!$value) return null;

        return $this->asDateTime($value)->format(self::DATE_FORMAT);
    }

    /**
     * @return int|null
     */
    public function getTypeAttribute()
    {
        if ( ! isset($this->attributes['type'])) return null;

        return (int) $this->attributes['type'];
    }

    public function getTypeTextAttribute()
    {
        static $types;

        if ( ! $types) $types = \Config::get('calc::client/types');

        return isset($this->attributes['type'], $types[$this->attributes['type']])
            ? $types[$this->attributes['type']] : '';
    }

    /* SETTERS */

    public function setFirstNameAtrribute($value)
    {
        $this->attributes['first_name'] = Str::title(sanitize($value));
    }

    public function setLastNameAtrribute($value)
    {
        $this->attributes['last_name'] = Str::title(sanitize($value));
    }

    /**
     * @param string $value
     */
    public function setTypeAtrribute($value)
    {
        $this->attributes['type'] = (int) $value;
    }

    /**
     * @param string $value
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = sanitize($value);
    }

    /**
     * @return null|string
     */
    public function setLastContactAtAttribute($value)
    {
        $this->attributes['last_contact_at'] = $this->createDate($value);
    }

    /**
     * @return null|string
     */
    public function setNextContactAtAttribute($value)
    {
        $this->attributes['next_contact_at'] = $this->createDate($value);
    }

    /**
     * @return null|string
     */
    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = $this->createDate($value);
    }

    /**
     * @return null|string
     */
    public function setPassportIssuedDateAttribute($value)
    {
        $this->attributes['passport_issued_date'] = $this->createDate($value);
    }

    /* SCOPES */

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param int                                   $type
     *
     * @return mixed
     */
    public function scopeType($q, $type)
    {
        if ($type = (int) $type)
        {
            $q->where('type', $type);
        }

        return $q;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param string                                $term
     *
     * @return mixed
     */
    public function scopeSearch($q, $term)
    {
        if ($term = sanitize((string) $term))
        {
            if (strpos($term, ' '))
            {
                list($last_name, $first_name) = explode(' ', $term, 2);
                $q->where('first_name', 'LIKE', "{$first_name}%");
                $q->Where('last_name', 'LIKE', "{$last_name}%");
            }
            else
            {
                $q->where('first_name', 'LIKE', "%{$term}%");
                $q->orWhere('last_name', 'LIKE', "%{$term}%");
                $q->orWhere('email', 'LIKE', "%{$term}%");
            }
        }

        return $q;
    }

    /**
     * @param     $q
     * @param int $manager
     *
     * @return mixed
     */
    public function scopeManager($q, $manager)
    {
        if ( ! $manager = (int) $manager) return $q;

        return $q->where('created_by', $manager);
    }

    /**
     * @param $q
     *
     * @return mixed
     */
    public function scopeOwn($q)
    {
        if (Auth::user()->isAdmin() || Auth::user()->isHeadManager())
        {
            return $q;
        }

        return $q->where('created_by', Auth::id());
    }

	public function files()
	{
		return $this->morphMany('Calc\Model\File', 'fileable');
	}

}
