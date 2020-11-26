<?php namespace Calc\Controller;

use Calc\Core\Controllers\BaseController;
use Calc\Model\Variable;

class ShatersPartsController extends BaseController
{
	protected $layout = 'calc::shaters_layout';

	public function index()
    {
        $this->title->prepend(trans('calc::titles.parts'));
        $this->layout->content = view('calc::shaters.parts.index')
            ->with('margin', Variable::find('margin'));
    }
}
