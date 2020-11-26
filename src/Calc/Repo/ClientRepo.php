<?php namespace Calc\Repo;

class ClientRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Client';

    public function findByEmail($email, $columns = array('*'))
    {
        return $this->model->query()->where('email', $email)->get($columns);
    }

    public function paginate(array $data = [])
    {
        $q = $this->query()->with('manager');

        $sort = array_get($data, 'sort');

        switch ($sort) {
            case 'users.last_name':
                $q->leftJoin('users', 'users.id', '=', 'clients.created_by');
                break;
        }

        $q->sort($sort, array_get($data, 'order'));
        $q->status(array_get($data, 'status'));
        $q->type(array_get($data, 'type'));
        $q->search(array_get($data, 'term'));
        $q->manager(array_get($data, 'manager'));
        $q->own();

        $paginator = $q->paginate((int) array_get($data, 'rows'));

        return $paginator;
    }

    /**
     * Удаление заказчика по ID
     *
     * @param int $id
     *
     * @return array
     * @throws \Exception
     */
    public function delete($id)
    {
        $obj = $this->find($id);

        $error = true;
        if ($obj->calculations()->count())
        {
            $message = trans('calc::client.delete_has_calculation', ['name' => $obj->present()->fullName]);
        }
        else if ( ! $obj->delete())
        {
            $message = trans('calc::client.delete_error', ['name' => $obj->present()->fullName]);
        }
        else
        {
            $error = false;
            $message = trans('calc::client.deleted', ['name' => $obj->present()->fullName]);
        }

        return [$message, $error];
    }
}
