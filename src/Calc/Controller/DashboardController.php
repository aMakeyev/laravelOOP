<?php namespace Calc\Controller;

use Calc\Core\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function getIndex()
    {
        $this->layout->content = view('calc::dashboard.index');
    }
}
