<?php namespace Calc\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const DATE_FORMAT = 'd.m.Y';

    /**
     * @var array
     */
    public static $sortable = [];

    public $perPage = 10;

    /**
     * Get sortables fields
     *
     * @return array
     */
    public function getSortable()
    {
        return static::$sortable;
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this, $options ?: JSON_UNESCAPED_UNICODE);
    }

    public function __toString()
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    public function scopeSort($q, $field = 'id', $order = 'asc')
    {
        return $q->orderBy(
            in_array($field, $this->getSortable()) ? $field : 'id',
            $order == 'asc' || $order == 'desc' ? $order : 'desc'
        );
    }

    /**
     * @param $value
     *
     * @return null|Carbon
     */
    public function createDate($value)
    {
        if ( ! $value) return null;

        return Carbon::createFromFormat(self::DATE_FORMAT, $value);
    }
}
