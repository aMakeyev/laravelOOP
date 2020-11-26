<?php namespace Calc\Controller;

use Calc\Model\Variable;
use Calc\Core\Controllers\BaseController;

class CoefficientsController extends BaseController
{
    public function index()
    {
        $this->title->prepend(trans('calc::titles.coefficients'));
        $this->layout->content = view('calc::coefficients.index')->with([
            'base_price' => Variable::find('base_price'),
        ]);
    }
}
