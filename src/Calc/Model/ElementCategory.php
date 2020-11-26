<?php namespace Calc\Model;

class ElementCategory extends BaseModel
{
    const FACADE = 1;
    const SKELETON = 2;
    const FURNITURE = 3;

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'elements_categories';

    /** RELATIONS */

    public function elements()
    {
        return $this->hasMany('Calc\Model\Element', 'category_id');
    }

    public function isFacade()
    {
        return $this->type == self::FACADE;
    }

    public function isSkeleton()
    {
        return $this->type == self::SKELETON;
    }

    public function isFurniture()
    {
        return $this->type == self::FURNITURE;
    }

}
