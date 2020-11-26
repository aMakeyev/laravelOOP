<?php namespace Calc\Repo;

class CalculationSubjectRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\CalculationSubject';

    /**
     * @param \Calc\Model\Calculation $parent
     * @param array $attributes
     *
     * @return \Calc\Model\CalculationSubject
     */
    public function createForParent($parent, array $attributes)
    {
        $obj = $this->model->newInstance($attributes);
        $parent->subjects()->save($obj);

        return $obj;
    }
}
