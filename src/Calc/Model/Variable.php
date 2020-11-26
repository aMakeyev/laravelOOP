<?php namespace Calc\Model;

use Calc\Model\Traits\Titleable;

class Variable extends BaseModel
{
    use Titleable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'variables';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [];
    public static $sortable = ['name','value','title'];

    public $timestamps = false;

    /* GETTERS */

    public function getValueAttribute()
    {
        if ( ! isset($this->attributes['value'])) return null;

        if (ctype_digit($this->attributes['value']))
        {
            $this->attributes['value'] = (int) $this->attributes['value'];
        }
        else if (is_numeric($this->attributes['value']))
        {
            $this->attributes['value'] = (float) $this->attributes['value'];
        }

        return $this->attributes['value'];
    }

    /* SETTERS */

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = sanitize($value);
    }

    public function setValueAttribute($value)
    {
        if (ctype_digit($value))     $value = (int) $value;
        else if (is_numeric($value)) $value = (float) $value;
        else                         $value = sanitize($value);

        $this->attributes['value'] = $value;
    }
}
