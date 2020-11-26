<?php namespace Calc\Controller\Api;

class ShatersGroupsPartsController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\ShatersPartGroupRepo';
    /**
     * @var \Calc\Repo\ShatersPartGroupRepo
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
