<?php namespace Calc\Controller;

use Calc\Model\Contractor;
use Calc\Core\Controllers\BaseController;

class ContractorsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->title->prepend(trans('calc::titles.contractors'));
    }

    public function index()
    {
        $this->layout->content = view('calc::contractors.index');
    }

    public function show($id)
    {
        /** @var Contractor $obj */
        $obj = Contractor::findOrFail($id);
        $this->title->prepend($obj->title);
        $this->layout->content = view('calc::contractors.show')->with('obj', $obj);
    }
}
