<?php namespace Calc\Repo;

class OrderRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Order';

    /**
     * Find own record by ID
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return \Calc\Model\Order
     */
    public function findOwn($id, $columns = ['*'])
    {
        return $this->model->own()->findOrFail($id, $columns);
    }

    public function findWithAllRelations($id)
    {
        $obj = $this->model->query()->with([
            'subject',
            'contractor',
            'calculation.manager',
            'calculation.client',
        ])->findOrFail($id);

        return $obj;
    }
}
