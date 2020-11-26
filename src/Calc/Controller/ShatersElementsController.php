<?php namespace Calc\Controller;

use Calc\Model\ShatersElementCategory;
use Calc\Core\Controllers\BaseController;

class ShatersElementsController extends BaseController
{
	protected $layout = 'calc::shaters_layout';

	public function index()
    {
        $this->title->prepend(trans('calc::titles.elements'));

        $this->layout->content = view('calc::shaters.elements.index')->with(
            'categories', ShatersElementCategory::with([
                'elements' => function ($q)
                {
                    return $q->orderBy('sort');
                }
            ])->orderBy('sort')->get()
        );
    }
}
