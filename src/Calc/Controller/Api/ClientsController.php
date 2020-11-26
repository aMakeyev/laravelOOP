<?php namespace Calc\Controller\Api;

use Calc\Model\Client;
use Input;
use Response;
use View;
use Calc\Validators\ClientValidator;

class ClientsController extends BaseController
{
    const MODAL_ID = '#client_modal';
    const FORM_ID = '#client_form';

    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\ClientRepo';
    /**
     * @var \Calc\Repo\ClientRepo
     */
    protected $repository;

    /**
     * Список заказчиков
     *
     * @return Response
     */
    public function index()
    {
        /** @var \Illuminate\Database\Query\Builder $q */
        $paginator = $this->repository->paginate(Input::all());

        return $this->response->data([
            'total' => $paginator->getTotal(),
            'rows' => $paginator->getItems(),
        ]);
    }

    /**
     * Форма добавления нового клиента
     *
     * @return Response
     */
    public function create()
    {
        return $this->response->data([
            'modal' => view('calc::clients.modal')->with(
                'obj', $this->repository->newEmpty()
            )->render(),
            'modal_id' => self::MODAL_ID,
        ]);
    }

    /**
     * Запись нового клиента в базу
     * POST /api/clients
     *
     * @return Response
     */
    public function store()
    {
        $data = [
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'status' => Input::get('status'),
            'type' => Input::get('type'),
            'last_contact_at' => Input::get('last_contact_at'),
            'next_contact_at' => Input::get('next_contact_at'),
            'description' => Input::get('description'),
            'subscribe' => Input::get('subscribe'),
            'delivery_address' => Input::get('delivery_address'),
            'second_name' => Input::get('second_name'),
            'birthday' => Input::get('birthday'),
            'passport_series' => Input::get('passport_series'),
            'passport_number' => Input::get('passport_number'),
            'passport_issued_by' => Input::get('passport_issued_by'),
            'passport_issued_code' => Input::get('passport_issued_code'),
            'passport_issued_date' => Input::get('passport_issued_date'),
            'passport_address' => Input::get('passport_address'),
        ];

        $validator = new ClientValidator($data, 'create');

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors' => $validator->getErrors(),
            ]);
        }

        $obj = $this->repository->create($data);

        if ( ! $obj)
        {
            return $this->response->message(
                trans('calc::client.create_error', ['name' => $obj->title,])
            );
        }

        return $this->response->message(trans('calc::client.created', [
            'name' => $obj->present()->fullName,
        ]))->data([
            'obj' => $obj->toArray(),
        ]);
    }

    /**
     * Получение заказчика
     * GET /api/clients/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Форма редактирования заказчика
     * GET /api/clients/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
		$obj = Client::with([
			'files'
		])->findOrFail($id);

        return $this->response->data([
            'modal' => view('calc::clients.modal')->with('obj', $obj)->render(),
            'modal_id' => self::MODAL_ID,
        ]);
    }

    /**
     * Сохранение заказчика
     * PUT /api/clients/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $data = input_only(
            'email',
            'phone',
            'first_name',
            'last_name',
            'status',
            'type',
            'last_contact_at',
            'next_contact_at',
            'description',
            'subscribe',
            'delivery_address',
            'second_name',
            'birthday',
            'passport_series',
            'passport_number',
            'passport_issued_by',
            'passport_issued_code',
            'passport_issued_date',
            'passport_address',
			'legal_name',
			'inn',
			'kpp',
			'legal_address',
			'mail_address',
			'bank_name',
			'settlement_account',
			'correspondent_account',
			'bik',
			'okpo',
			'okato',
			'oktmo',
			'okved',
			'ogrn',
			'legal_phone',
			'legal_email',
			'director_name',
			'files'
        );

		$validator = new ClientValidator($data, 'update');

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors' => $validator->getErrors(),
            ]);
        }

        if ( ! $obj = $this->repository->update($id, $data))
        {
            return $this->response->error(
                trans('calc::client.update_error', ['name' => $obj->present()->fullName,])
            );
        }

        return $this->response->message(
            trans('calc::client.updated', ['name' => $obj->present()->fullName,])
        );
    }

    /**
     * Удаление заказчика
     * DELETE /api/clients/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        list($message, $error) = $this->repository->delete($id);

        if ($error) $this->response->error();

        return $this->response->message($message);
    }
}
