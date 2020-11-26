<?php namespace Calc\Model;

use Auth;
use Str;
use Calc\Presenters\PresentableTrait;
use Calc\Model\Traits\Statusable;
use Calc\Model\Traits\Titleable;
use Calc\Model\Traits\Descriptionable;

class Contractor extends BaseModel
{
    use PresentableTrait, Statusable, Titleable, Descriptionable;

    protected $presenter = 'ContractorPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contractors';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [
        'title', 'last_name', 'first_name', 'phone', 'email',
        'description', 'status', 'function', 'address'
    ];

    public static $sortable = [
        'title', 'last_name', 'first_name', 'phone', 'email', 'status', 'function'
    ];

    /** EVENTS */

    public static function boot()
    {
        parent::boot();

        self::creating(function (self $model)
        {
            if ( ! Auth::check())
            {
                return false;
            }

            $model->created_by = Auth::user()->id;
        });

        self::saving(function (self $model)
        {
            // Первая буква в имени и фамилии заглавная
            $model->first_name = Str::title($model->first_name);
            $model->last_name = Str::title($model->last_name);
        });

        // Удаление подрядчика
        self::deleting(function (self $model)
        {
            // Если есть заказы / подряды не удаляем подрядчика
            if ($model->orders()->count())
            {
                return false;
            }
        });
    }

    /** RELATIONS */

    public function orders()
    {
        return $this->hasMany('Calc\Model\Order', 'contractor_id');
    }

    /**
     * Метод для получение экземпляра менеджераа добавившего клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('Calc\Model\User', 'created_by');
    }

    public function manager()
    {
        return $this->belongsTo('Calc\Model\SimpleManager', 'created_by');
    }

    /** SETTERS */

    public function setFirstNameAtrribute($value)
    {
        $this->attributes['first_name'] = sanitize($value);
    }

    public function setLastNameAtrribute($value)
    {
        $this->attributes['last_name'] = sanitize($value);
    }

    public function setFunctionAtrribute($value)
    {
        $this->attributes['function'] = sanitize($value);
    }

    public function setPhoneAtrribute($value)
    {
        $this->attributes['phone'] = sanitize($value);
    }

    public function setAddressAtrribute($value)
    {
        $this->attributes['address'] = sanitize($value);
    }

    public function setDescriptionAtrribute($value)
    {
        $this->attributes['description'] = sanitize($value);
    }

    /* SCOPES */

    public function scopeSearchByTitle($q, $term)
    {
        if ( ! $term = trim((string) $term))
        {
            return $q;
        }

        $q->where('title', 'LIKE', "%{$term}%");
        $q->orWhere('email', 'LIKE', "%{$term}%");
        $q->orWhere('phone', 'LIKE', "%{$term}%");

        return $q;
    }

    /**
     * @param $q
     * @param int $manager
     *
     * @return mixed
     */
    public function scopeManager($q, $manager)
    {
        if ( ! $manager = (int) $manager)
        {
            return $q;
        }

        return $q->where('created_by', $manager);
    }
}
