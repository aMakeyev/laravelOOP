<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class UserRole extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_roles';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = ['title'];
    public static $sortable = ['title'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany('Calc\Model\User', 'role_id');
    }
}
