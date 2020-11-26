<?php namespace Calc\Model;

use Str;
use Hash;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Calc\Presenters\PresentableTrait;
use Calc\Model\Traits\Statusable;

class User extends BaseModel implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, PresentableTrait, Statusable;

    const ADMIN = 1;
    const MANAGER = 2;
    const HEAD_MANAGER = 3;
    const SHATERS_MANAGER = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $presenter = 'UserPresenter';

    protected $fillable = [
        'username', 'password', 'last_name', 'first_name',
        'phone', 'rate', 'status', 'role', 'email'
    ];

    protected $newPassword;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'persist_code', 'reset_password_code'
    ];

    public static $sortable = [
        'id', 'last_name', 'first_name', 'username', 'email', 'rate', 'created_at', 'last_login',
        'last_activity'
    ];

    protected $appends = ['calculations_count', 'date'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login', 'last_activity'];

    /* EVENTS */

    protected static function boot()
    {
        parent::boot();

        self::creating(function (self $model)
        {
            if (empty($model->password))
            {
                $model->password = Str::random(10);
            }
        });

        self::deleting(function (self $model)
        {
            if ($model->orders()->count()
                || $model->calculations()->count()
                || $model->count() === 1
            )
            {
                return false;
            }
        });

        self::saving(function (self $model)
        {
            if (empty($model->rate)) $model->rate = null;
            if (empty($model->phone)) $model->phone = null;
        });
    }

    /* RELATIONS */

    public function orders()
    {
        return $this->hasManyThrough('Calc\Model\Order', 'Calc\Model\Calculation', 'user_id', 'calculation_id');
    }

    public function clients()
    {
        return $this->hasManyThrough('Calc\Model\Client', 'Calc\Model\Calculation', 'user_id', 'calculation_id');
    }

    public function createdClients()
    {
        return $this->hasMany('Calc\Model\Client', 'created_by', 'id');
    }

    public function calculations()
    {
        return $this->hasMany('Calc\Model\Calculation');
    }

    public function countCalculations()
    {
        return $this->calculations()->select([
            DB::raw('count(*) as count'), 'user_id'
        ])->groupBy('user_id');
    }

    /* GETTERS */

    public function getCalculationsCountAttribute()
    {
        if ( ! $this->exists) return null;

        if ( ! array_key_exists('countCalculations', $this->relations))
        {
            $this->countCalculations;
        }

        $item = $this->countCalculations->first();

        return $item === null ? 0 : $item->count;
    }

    public function getDateAttribute()
    {
        if ( ! isset($this->attributes['created_at'])) return null;

        return $this->created_at->format('j.m.Y');
    }

    public function getNewPasswordAttribute()
    {
        return $this->newPassword;
    }

    /* SETTERS */

    public function setRoleAttribute($value)
    {
        $this->attributes['role'] = (int) $value;
    }

    public function setPasswordAttribute($value)
    {
        $value = trim((string) $value);
        if ($value !== '')
        {
            $this->attributes['password'] = Hash::make($value);
            $this->newPassword = $value;
        }
    }

    public function isAdmin()
    {
        return $this->exists && $this->role == self::ADMIN;
    }

    public function isManager()
    {
        return $this->exists && $this->role == self::MANAGER;
    }

    public function isHeadManager()
    {
        return $this->exists && $this->role == self::HEAD_MANAGER;
    }
	public function isShatersManager()
	{
		return $this->exists && $this->role == self::SHATERS_MANAGER;
	}
	public function isNotShatersManager()
	{
		return $this->exists && $this->role != self::SHATERS_MANAGER;
	}
	public function isNotManager()
	{
		return $this->exists && $this->role != self::MANAGER;
	}

    /* SCOPES */

    public function scopeRole($q, $role)
    {
        if ($role = (int) $role)
        {
            $q->where('role', $role);
        }

        return $q;
    }

    /**
     * @param $q
     *
     * @return mixed
     */
    public function scopeWithoutAdmin($q)
    {
        if (Auth::user()->isAdmin())
        {
            return $q;
        }

        return $q->where('role', '!=', 1);
    }

    public function touchActivity()
    {
        if ( ! $this->last_activity || Carbon::now()->diffInSeconds($this->last_activity) > 120)
        {
            DB::table($this->table)->where('id', $this->id)->update([
                'last_activity' => Carbon::now()
            ]);

            return true;
        }

        return false;
    }
}
