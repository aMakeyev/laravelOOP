<?php namespace Calc\Repo;

use Calc\Model\ElementCategory;

class ElementsRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Element';

    public function getList()
    {
        $categories = [];

        $_categories = ElementCategory::has('elements')->with([
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
        $categories = ElementCategory::with([
            'elements' => function ($q)
            {
                $q->orderBy('sort');
            }
        ])->orderBy('sort')->get();

        return $categories;
    }

}
