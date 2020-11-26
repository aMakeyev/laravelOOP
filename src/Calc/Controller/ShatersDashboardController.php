<?php namespace Calc\Controller;

use Calc\Core\Controllers\BaseController;

class ShatersDashboardController extends BaseController
{
	protected $layout = 'calc::shaters_layout';

    public function getIndex()
    {
        $this->layout->content = view('calc::shaters.dashboard.index');
    }
}
