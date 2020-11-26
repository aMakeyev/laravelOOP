<?php namespace Calc\Repo;

class PartGroupRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\PartGroup';

    public function jsonList($prepend = false)
    {
        return json_encode($this->lists($prepend), JSON_UNESCAPED_UNICODE);
    }

    public function lists($prepend = false, $toArray = true)
    {
        $result = $this->all();
        if ($prepend)
        {
            $result->prepend((object) ['id' => 0, 'title' => 'Все']);
        }

        return $toArray ? $result->toArray() : $result;
    }
}
