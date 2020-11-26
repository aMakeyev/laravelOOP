<?php namespace Calc\Repo;

class ShatersSubjectElementRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\ShatersSubjectElement';

    /**
     * @param \Calc\Model\ShatersCalculationSubject $parent
     * @param array $attributes
     *
     * @return \Calc\Model\ShatersSubjectElement
     */
    public function createForParent($parent, array $attributes)
    {
        $obj = $this->model->newInstance($attributes);
        $parent->elements()->save($obj);

        return $obj;
    }
}
