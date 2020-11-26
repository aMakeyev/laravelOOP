<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class PartGroup extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'parts_groups';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = ['title'];

    public $timestamps = false;

    /* RELATIONS */

    public function parts()
    {
        return $this->hasMany('Calc\Model\Part', 'group_id');
    }
}
