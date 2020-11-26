<?php namespace Calc\Controller\Api;

use Illuminate\Routing\Controller;
use Calc\Helpers\Response;

class BaseController extends Controller
{
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var string
     */
    protected $repositoryClassName;
    /**
     * @var \Calc\Repo\Repo
     */
    protected $repository;
    /**
     * @var \Calc\Model\User
     */
    protected $user;

    function __construct()
    {
        $this->response = new Response;

        if (isset($this->repositoryClassName))
        {
            $this->repository = app($this->repositoryClassName);
        }

        $this->user = user();
    }
}
