<?php namespace Calc\Repo;

class SubjectElementRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\SubjectElement';

    /**
     * @param \Calc\Model\CalculationSubject $parent
     * @param array $attributes
     *
     * @return \Calc\Model\SubjectElement
     */
    public function createForParent($parent, array $attributes)
    {
        $obj = $this->model->newInstance($attributes);
        $parent->elements()->save($obj);

        return $obj;
    }
}
