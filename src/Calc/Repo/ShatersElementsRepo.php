<?php namespace Calc\Repo;

use Calc\Model\ShatersElementCategory;

class ShatersElementsRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\ShatersElement';

    public function getList()
    {
        $categories = [];

        $_categories = ShatersElementCategory::has('elements')->with([
            'elements' => function ($q)
            {
                $q->orderBy('sort');
            }
        ])->orderBy('sort')->get();

        foreach ($_categories as $c)
        {
            $categories[$c->title] = $c->elements->lists('title', 'id');
        }

        return $categories;
    }

    public function tree()
    {
        $categories = ShatersElementCategory::with([
            'elements' => function ($q)
            {
                $q->orderBy('sort');
            }
        ])->orderBy('sort')->get();

        return $categories;
    }

}
