<?php namespace Calc\Controller\Api;

use Input;
use Calc\Model\ConstructorRate;

class ConstructorsRatesController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\ConstructorRateRepo';
    /**
     * @var \Calc\Repo\ConstructorRateRepo
     */
    protected $repository;

    /**
     * Список ставок конструкторов
     *
     * @return \Response
     */
    public function index()
    {
        return $this->response->data([
            'rows'  => $this->repository->all()->toArray(),
        ]);
    }

    /**
     * Запись ставки конструктора
     * POST /api/constructors-rates
     *
     * @return \Response
     */
    public function store()
    {
        return $this->response->message('Ставки конструкторов сохранены')->data(
            $this->repository->createAndUpdate(Input::get('data', []))
        );
    }
}
