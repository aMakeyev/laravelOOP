<?php namespace Calc\Repo;

class SendmailRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Sendmail';

    public function paginate(array $data = [])
    {
        $q = $this->query();

        $sort = array_get($data, 'sort');

        $q->sort($sort, array_get($data, 'order'));
        $q->status(array_get($data, 'status'));
        $q->target(array_get($data, 'target'));

        $paginator = $q->paginate((int)array_get($data, 'rows'));

        return $paginator;
    }
}
