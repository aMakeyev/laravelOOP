<?php namespace Calc\Controller\Api;

use Input;
use Response;
use Auth;
use View;
use Mail;
use Calc\Model\User;
use Calc\Validators\UserValidator as UserValidator;

class ManagersController extends BaseController
{
    const MODAL_ID = '#user_modal';
    const FORM_ID  = '#user_form';

    /**
     * @var \Calc\Repo\UserRepo
     */
    protected $repository;
    protected $repositoryClassName = 'Calc\Repo\UserRepo';

    /**
     * Список менеджеров
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
     * Форма добавления менеджера
     *
     * @return Response
     */
    public function create()
    {
        return $this->response->data([
            'modal' => view('calc::managers.modal')->with('obj', new User)->render(),
            'modal_id' => self::MODAL_ID
        ]);
    }

    /**
     * Сохранение нового менеджера в базе данных
     * POST /api/manager
     *
     * @return Response
     */
    public function store()
    {
        $data = [
            'username'              => Input::get('username'),
            'first_name'            => Input::get('first_name'),
            'last_name'             => Input::get('last_name'),
            'email'                 => Input::get('email'),
            'phone'                 => Input::get('phone'),
            'rate'                  => Input::get('rate'),
            'role'                  => Input::get('role'),
            'status'                => Input::get('status'),
            'password'              => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        ];

        $validator = new UserValidator($data, 'create');

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors'  => $validator->getErrors()
            ]);
        }

        $obj = new User($data);

        if ( ! $obj->save())
        {
            return $this->response->message(
                trans('calc::manager.create_error', [
                    'name' => $obj->present()->fullName
                ])
            );
        }

        $this->repository->sendEmailToNewUser($obj);

        return $this->response->message(
            trans('calc::manager.created', [
                'name' => $obj->present()->fullName
            ])
        );
    }

    /**
     * Display the specified resource.
     * GET /api/managers/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->repository->find($id);

        return $user;
    }

    /**
     * Форма редактирования менеджера
     * GET /api/managers/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->repository->find($id);

        return $this->response->data([
            'modal'    => view('calc::managers.modal')->with('obj', $user)->render(),
            'modal_id' => self::MODAL_ID
        ]);
    }

    /**
     * Редактирование менеджера
     * PUT /api/managers/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        /** @var User $obj */
        $obj = $this->repository->find($id);

        $data = [
            'username'              => Input::get('username'),
            'first_name'            => Input::get('first_name'),
            'last_name'             => Input::get('last_name'),
            'email'                 => Input::get('email'),
            'phone'                 => Input::get('phone'),
            'rate'                  => Input::get('rate'),
            'role'                  => Input::get('role'),
            'status'                => Input::get('status'),
            'password'              => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        ];

        $validator = new UserValidator($data, 'update');
        UserValidator::$rules['update']['username'][4] .= ',' . $id;
        UserValidator::$rules['update']['email'][2] .= ',' . $id;

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors'  => $validator->getErrors()
            ]);
        }

        if ( ! $obj->update($data))
        {
            return $this->response->message(trans('calc::manager.update_error', ['name' => $obj->present()->fullName]));
        }

        return $this->response->message(trans('calc::manager.updated'));

    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/managers/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id === (int) $id)
        {
            return $this->response->error('Нельзя удалить самого себя');
        }

        /** @var User $obj */
        $obj = $this->repository->find($id);

        if ($obj->orders()->count())
        {
            return $this->response->error(trans('calc::manager.delete_has_orders'));
        }

        if ($obj->calculations()->count())
        {
            return $this->response->error(trans('calc::manager.delete_has_calc'));
        }

        if ( ! $obj->delete())
        {
            return $this->response->error(
                trans('calc::manager.delete_error', ['name' => $obj->present()->fullName])
            );
        }

        return $this->response->message(
            trans('calc::manager.deleted', ['name' => $obj->present()->fullName])
        );
    }
}
