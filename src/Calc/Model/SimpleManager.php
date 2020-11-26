<?php namespace Calc\Model;

use Calc\Presenters\PresentableTrait;

class SimpleManager extends BaseModel
{
    use PresentableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $presenter = 'UserPresenter';

    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'id', 'last_name', 'first_name', 'phone'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function () {
            return false;
        });
        static::deleting(function () {
            return false;
        });
        static::saving(function () {
            return false;
        });
        static::updating(function () {
            return false;
        });
    }
}
