<?php namespace Calc\Controller;

use Calc\Model\Client;
use Calc\Core\Controllers\BaseController;
use Calc\Model\Sendmail;
use Calc\Repo\SendmailRepo;

class DocsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->title->prepend(trans('calc::titles.docs'));
    }

    public function index()
    {
        $this->layout->content = view('calc::docs.index');
    }
}
