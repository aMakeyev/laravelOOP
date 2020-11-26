<?php namespace Calc\Model;

use Auth;
use Config;
use Calc\Model\Traits\Titleable;
use Calc\Presenters\PresentableTrait;

class Part extends BaseModel
{
    use PresentableTrait, Titleable;

    protected $presenter = 'PartPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'parts';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $fillable = ['title','article','unit','price','unit_price','group_id'];
    public static $sortable = ['title','article','unit','price','unit_price'];
    protected static $units;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['unit_id'];

    /* EVENTS */

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (self $model)
        {
            if ($model->subjectElements()->count())
            {
                return false;
            }
        });
    }

    /** RELATIONS */

    public function group()
    {
        return $this->belongsTo('Calc\Model\PartGroup');
    }

    public function subjectElements()
    {
        return $this->hasMany('Calc\Model\SubjectElement', 'part_id');
    }

    /** GETTERS */

    public function getUnitAttribute()
    {
        if ( ! isset($this->attributes['unit'])) return null;

        if ( ! isset(self::$units))
        {
            self::$units = Config::get("calc::part/units");
        }

        return self::$units[$this->attributes['unit']];
    }

    public function getUnitIdAttribute()
    {
        if ( ! isset($this->attributes['unit'])) return null;

        return (int) $this->attributes['unit'];
    }

    public function getUnitPriceAttribute()
    {
        if ( ! isset($this->attributes['unit_price'])) return null;

        return (float) $this->attributes['unit_price'];
    }

    /** SETTERS */

    /**
     * Set part unit
     *
     * @param $value
     */
    public function setUnitAttribute($value)
    {
        $this->attributes['unit'] = (int) $value;
    }

    public function setArticleAttribute($value)
    {
        if ($this->query()->articleExists($this, $value)->exists())
        {
            $i = 1;
            while ($this->query()->articleExists($this, $value . '.' . $i)->exists())
            {
                $i++;
            }

            $value .= '.' . $i;
        }

        $this->attributes['article'] = $value;
    }

    /* SCOPES */

    public function scopeArticleExists($query, $model, $value)
    {
        $query->whereArticle($value);

        if ($model->exists)
        {
            $query->where('id', '!=', $model->id);
        }

        return $query;
    }

    public function scopeGroup($q, $group)
    {
        if ($group = (int) $group)
        {
            $q->where('group_id', $group);
        }

        return $q;
    }

    public function scopeSearch($q, $term)
    {
        if ($term = trim((string) $term))
        {
            $q->where('title', 'LIKE', "%{$term}%");
        }
    }

    public function scopeSelectForManagers($q)
    {
        if (Auth::user()->isAdmin())
        {
            return $q;
        }

        $q->select(['id','group_id','title','article','unit']);
    }
}
