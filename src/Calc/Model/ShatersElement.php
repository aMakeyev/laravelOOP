<?php namespace Calc\Model;

class ShatersElement extends BaseModel
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shaters_elements';

    /** RELATIONS */

    public function category()
    {
        return $this->belongsTo('Calc\Model\ShatersElementCategory', 'category_id');
    }

    public function subjectElements()
    {
        return $this->hasMany('Calc\Model\ShatersSubjectElement', 'character');
    }
}
