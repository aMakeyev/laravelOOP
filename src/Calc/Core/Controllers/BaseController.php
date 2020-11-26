<?php namespace Calc\Core\Controllers;

use App;
use Auth;
use Illuminate\Routing\Controller;
use View;

class BaseController extends Controller
{
    protected $layout = 'calc::layout';

    /**
     * @var \Calc\Model\User
     */
    protected $user;

    /**
     * @var \Calc\Helpers\Title
     */
    protected $title;

    /**
     * @var string
     */
    protected $repositoryClassName;

    /**
     * @var \Calc\Repo\Repo
     */
    protected $repository;

    /**
     * BaseController constructor
     */
    function __construct()
    {
        if (isset($this->repositoryClassName))
        {
            $this->repository = App::make($this->repositoryClassName);
        }

        $this->title = App::make('Title', [trans('calc::titles.title')]);

        $this->user = Auth::user();
    }

    /**
     * Setup layout
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            View::share('user', $this->user);
            $this->layout = view($this->layout);
            $this->layout->title = $this->title;
        }
    }
}
