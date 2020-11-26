<?php namespace Calc\Repo;

class ContractorRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Contractor';

    public function findByEmail($email, $columns = array('*'))
    {
        return $this->query()->where('email', $email)->get($columns);
    }

    public function findByPhone($phone, $columns = array('*'))
    {
        return $this->query()->where('phone', $phone)->get($columns);
    }

    public function paginate(array $data = [])
    {
        $q = $this->query();

        $q->sort(array_get($data, 'sort'), array_get($data, 'order'));
        $q->status(array_get($data, 'status'));
        $q->searchByTitle(array_get($data, 'term'));

        $paginator = $q->paginate((int) array_get($data, 'rows'));

        return $paginator;
    }
}
