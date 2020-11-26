<?php namespace Calc\Repo;

class PartRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Part';

    public function findByArticle($article, $columns = array('*'))
    {
        return $this->model->whereArticle($article)->first($columns);
    }

    public function paginate(array $data = [])
    {
        $q = $this->query();

        $q->sort(array_get($data, 'sort'), array_get($data, 'order'));
        $q->group(array_get($data, 'group'));
        $q->search(array_get($data, 'term'));
        $q->selectForManagers();

        $paginator = $q->paginate((int) array_get($data, 'rows'));

        return $paginator;
    }
}
