<?php namespace Calc\Repo;

class ShatersPartGroupRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\ShatersPartGroup';

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
