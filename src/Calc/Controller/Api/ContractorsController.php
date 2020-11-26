<?php namespace Calc\Controller\Api;

use Input;
use Response;
use View;
use Calc\Model\Contractor;
use Calc\Validators\ContractorValidator;

class ContractorsController extends BaseController
{
    const MODAL_ID = '#contractor_modal';
    const FORM_ID  = '#contractor_form';

    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\ContractorRepo';
    /**
     * @var \Calc\Repo\ContractorRepo
     */
    protected $repository;

    /**
     * Список подрядчиков
     *
     * @return Response
     */
    public function index()
    {
        $paginator = $this->repository->paginate(Input::all());

        return $this->response->data([
            'total' => $paginator->getTotal(),
            'rows'  => $paginator->getItems(),
        ]);
    }

    /**
     * Форма добавления нового подрядчика
     *
     * @return Response
     */
    public function create()
    {
        return $this->response->data([
            'modal' => view('calc::contractors.modal')->with(
                'obj', $this->repository->newEmpty()
            )->render(),
            'modal_id' => self::MODAL_ID
        ]);
    }

    /**
     * Запись нового подрядчика в базу
     * POST /api/contractors
     *
     * @return Response
     */
    public function store()
    {
        $obj = $this->repository->newEmpty();

        $data = [
            'title'       => Input::get('title'),
            'first_name'  => Input::get('first_name'),
            'last_name'   => Input::get('last_name'),
            'function'    => Input::get('function'),
            'email'       => Input::get('email'),
            'phone'       => Input::get('phone'),
            'status'      => Input::get('status'),
            'description' => Input::get('description'),
        ];

        $validator = new ContractorValidator($data, 'create');

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors'  => $validator->getErrors()
            ]);
        }

        // Create obj
        $obj->fill($data);

        if ( ! $obj->save())
        {
            return $this->response->message(
                trans('calc::contractor.create_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(
            trans('calc::contractor.created', ['name' => $obj->title])
        );
    }

    /**
     * Получение подрядчика
     * GET /api/contractors/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $obj = $this->repository->find($id);

        return $obj;
    }

    /**
     * Форма редактирования подрядчика
     * GET /api/contractors/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $obj = $this->repository->find($id);

        return $this->response->data([
            'modal'    => view('calc::contractors.modal')->with('obj', $obj)->render(),
            'modal_id' => self::MODAL_ID
        ]);
    }

    /**
     * Сохранение подрядчика
     * PUT /api/contractors/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        /** @var Contractor $obj */
        $obj = $this->repository->find($id);

        $data = [
            'first_name'  => Input::get('first_name'),
            'last_name'   => Input::get('last_name'),
            'email'       => Input::get('email'),
            'phone'       => Input::get('phone'),
            'status'      => Input::get('status'),
            'function'    => Input::get('function'),
            'description' => Input::get('description'),
            'address'     => Input::get('address'),
            'title'       => Input::get('title'),
        ];

        $validator = new ContractorValidator($data, 'update');

        ContractorValidator::$rules['update']['email'][2] .= ',' . $id;

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors'  => $validator->getErrors()
            ]);
        }

        if ( ! $obj->update($data))
        {
            return $this->response->message(
                trans('calc::contractor.update_error', ['name' => $obj->title]));
        }

        return $this->response->message(trans('calc::contractor.updated'));

    }

    /**
     * Удаление подрядчика
     * DELETE /api/contractors/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Contractor $obj */
        $obj = $this->repository->find($id);

        if ($obj->orders()->count())
        {
            return $this->response->error(
                trans('calc::contractor.delete_has_orders', ['name' => $obj->title])
            );
        }

        if ( ! $obj->delete())
        {
            return $this->response->error(
                trans('calc::contractor.delete_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(
            trans('calc::contractor.deleted', ['name' => $obj->title])
        );
    }
}
