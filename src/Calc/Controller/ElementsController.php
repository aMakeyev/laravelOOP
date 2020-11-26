<?php namespace Calc\Controller;

use Calc\Model\ElementCategory;
use Calc\Core\Controllers\BaseController;

class ElementsController extends BaseController
{
    public function index()
    {
        $this->title->prepend(trans('calc::titles.elements'));

        $this->layout->content = view('calc::elements.index')->with(
            'categories', ElementCategory::with([
                'elements' => function ($q)
                {
                    return $q->orderBy('sort');
                }
            ])->orderBy('sort')->get()
        );
    }
}
