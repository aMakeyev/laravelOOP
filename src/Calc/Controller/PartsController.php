<?php namespace Calc\Controller;

use Calc\Core\Controllers\BaseController;
use Calc\Model\Variable;

class PartsController extends BaseController
{
    public function index()
    {
        $this->title->prepend(trans('calc::titles.parts'));
        $this->layout->content = view('calc::parts.index')
            ->with('margin', Variable::find('margin'));
    }
}
