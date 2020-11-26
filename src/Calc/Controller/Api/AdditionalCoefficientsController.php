<?php namespace Calc\Controller\Api;

use Input;

class AdditionalCoefficientsController extends BaseController
{
    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\AdditionalCoefficientRepo';
    /**
     * @var \Calc\Repo\AdditionalCoefficientRepo
     */
    protected $repository;

    /**
     * Список дополнительных коэффициентов
     * GET /api/additional-coefficient
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
     * Создание и обновдение дополнительных коэфициентов
     * POST /api/additional-coefficient
     *
     * @return \Response
     */
    public function store()
    {
        return $this->response->message('Ставки сохранены')->data(
            $this->repository->createAndUpdate(Input::get('data', []))
        );
    }
}
