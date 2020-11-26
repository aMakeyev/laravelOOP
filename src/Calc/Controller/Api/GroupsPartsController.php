<?php namespace Calc\Controller\Api;

class GroupsPartsController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\PartGroupRepo';
    /**
     * @var \Calc\Repo\PartGroupRepo
     */
    protected $repository;

    /**
     * Список категорий
     */
    public function index()
    {
        return $this->repository->lists(true);
    }
}
