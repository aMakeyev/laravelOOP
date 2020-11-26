<?php namespace Calc\Repo;

class ShatersPartRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\ShatersPart';

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
