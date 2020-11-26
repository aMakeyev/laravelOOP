<?php namespace Calc\Repo;

class ShatersCalculationSubjectRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\ShatersCalculationSubject';

    /**
     * @param \Calc\Model\ShatersCalculation $parent
     * @param array $attributes
     *
     * @return \Calc\Model\ShatersCalculationSubject
     */
    public function createForParent($parent, array $attributes)
    {
        $obj = $this->model->newInstance($attributes);
        $parent->subjects()->save($obj);

        return $obj;
    }
}
