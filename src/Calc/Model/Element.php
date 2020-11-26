<?php namespace Calc\Model;

class Element extends BaseModel
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'elements';

    /** RELATIONS */

    public function category()
    {
        return $this->belongsTo('Calc\Model\ElementCategory', 'category_id');
    }

    public function subjectElements()
    {
        return $this->hasMany('Calc\Model\SubjectElement', 'character');
    }
}
