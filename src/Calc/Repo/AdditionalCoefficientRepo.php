<?php namespace Calc\Repo;

class AdditionalCoefficientRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\AdditionalCoefficient';

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
            if (isset($item['title']) && isset($item['value']))
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
            $delete = $this->query()->whereNotIn('id', $notDelete)->get();

            foreach ($delete as $d)
            {
                if ($d->delete()) $deleted += 1;
            }
        }

        return compact('created','updated','deleted');
    }
}
