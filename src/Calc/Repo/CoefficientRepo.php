<?php namespace Calc\Repo;

class CoefficientRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Coefficient';

    public function paginate(array $data = [])
    {
        $q = $this->query();

        $q->sort(array_get($data, 'sort'), array_get($data, 'order'));

        $paginator = $q->paginate((int) array_get($data, 'rows'));

        return $paginator;
    }

    public function createAndUpdate(array $items)
    {
        $updated = 0;
        $created = 0;
        $deleted = 0;
        $notDelete = [];
        foreach ($items as $item)
        {
            if (isset($item['range_start']) && isset($item['range_end']) && isset($item['value']))
            {
                if (isset($item['isNewRecord']))
                {
                    $record = $this->create($item);
                    $created++;
                }
                else
                {
                    $record = $this->update($item['id'], $item);
                    if ($record) $updated++;
                }

                if ($record) $notDelete[] = $record->id;
            }
        }

        if ($notDelete)
        {
            $deleted = $this->query()->whereNotIn('id', $notDelete)->count();
            $this->query()->whereNotIn('id', $notDelete)->delete();
        }

        return compact('created','updated','deleted');
    }
}
