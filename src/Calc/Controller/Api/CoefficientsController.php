<?php namespace Calc\Controller\Api;

use Input;

class CoefficientsController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\CoefficientRepo';
    /**
     * @var \Calc\Repo\CoefficientRepo
     */
    protected $repository;

    /**
     * Список коэффициентов
     * GET /api/coefficient
     *
     * @return \Response
     */
    public function index()
    {
        return $this->response->data([
            'rows'  => $this->repository->all()->toArray()
        ]);
    }

    /**
     * Создание и обновление коэфициентов
     * POST /api/coefficient
     *
     * @return \Response
     */
    public function store()
    {
        return $this->response->message('Коэффициенты сохранены')->data(
            $this->repository->createAndUpdate(Input::get('data', []))
        );
    }
}
