<?php namespace Calc\Controller\Api;

use Calc\Model\Sendmail;
use Calc\Validators\SendmailValidator;
use Input;
use Response;
use View;
use Calc\Validators\ClientValidator;

class SendmailsController extends BaseController
{
    const MODAL_ID = '#sendmail_modal';
    const FORM_ID = '#sendmail_form';

    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\SendmailRepo';
    /**
     * @var \Calc\Repo\SendmailRepo
     */
    protected $repository;

    /**
     * Список рассылок
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
            'modal' => view('calc::sendmails.modal')->with(
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
            'target' => Input::get('target'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
        ];

        /*try
        {
            $obj = $this->repository->upload($fileableType, $fileableId, Input::file('file'));
        }
        catch (\Exception $e)
        {
            return $this->response->error($e->getMessage());
        }

        if ($obj === null)
        {
            return $this->response->error('Неизвестная ошибка');
        }

        return $this->response->message("Файл \"{$obj->name}\" успешно загружен")->data([
            'file' => $obj->toArray()
        ]);*/

        $validator = new SendmailValidator($data, 'create');

        if ( ! $validator->passes()) {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors' => $validator->getErrors(),
            ]);
        }

        if ($filename = Sendmail::saveFile()) {
            $data['file'] = $filename;
        }

        $obj = $this->repository->create($data);

        if ( ! $obj) {
            return $this->response->message(
                trans('calc::sendmail.create_error', [])
            );
        }

        return $this->response->message(trans('calc::sendmail.created', []))->data([
            'obj' => $obj->toArray(),
        ]);
    }

    /**
     * Получение заказчика
     * GET /api/sendmails/{id}
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
        $obj = $this->repository->find($id);

        return $this->response->data([
            'modal' => view('calc::sendmails.modal')->with('obj', $obj)->render(),
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
            'target',
            'subject',
            'body'
        );

        $validator = new SendmailValidator($data, 'update');

        if ( ! $validator->passes()) {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors' => $validator->getErrors(),
            ]);
        }

        if ($filename = Sendmail::saveFile()) {
            $data['file'] = $filename;
        }

        if ( ! $obj = $this->repository->update($id, $data)) {
            return $this->response->error(
                trans('calc::sendmail.update_error', [])
            );
        }

        return $this->response->message(
            trans('calc::sendmail.updated', [])
        );
    }

    /**
     * Удаление рассылки
     * DELETE /api/sendmails/{id}
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

    /**
     * @param $id
     * @return \Calc\Helpers\Response
     */
    public function fileDelete($id)
    {
        $data = [
            'file' => '',
        ];

        if ( ! $obj = $this->repository->update($id, $data)) {
            return $this->response->error(
                trans('calc::sendmail.update_error', [])
            );
        }

        return $this->response->message(
            trans('calc::sendmail.updated', [])
        );

    }
}
