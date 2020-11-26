<?php namespace Calc\Controller;

use Calc\Model\User;
use Calc\Core\Controllers\BaseController;

class ManagersController extends BaseController
{
    function __construct()
    {
        parent::__construct();

        $this->title->prepend(trans('calc::titles.managers'));
    }

    public function index()
    {
        $this->layout->content = view('calc::managers.index');
    }

    public function show($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $this->title->prepend($user->present()->fullName);
        $this->layout->content = view('calc::managers.show')->with('obj', $user);
    }
}
