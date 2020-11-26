<?php namespace Calc\Controller\Api;

use Input;
use View;
use Response;
use Calc\Model\PartGroup;
use Calc\Validators\PartValidator;

class PartsController extends BaseController
{
    const MODAL_ID = '#part_modal';
    const FORM_ID  = '#part_form';

    /**
     * @var string
     */
    protected $repositoryClassName = 'Calc\Repo\PartRepo';
    /**
     * @var \Calc\Repo\PartRepo
     */
    protected $repository;

    /**
     * Список комплектующих
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
            'modal' => view('calc::parts.modal')
                ->with('obj', $this->repository->newEmpty())
                ->with('groups', PartGroup::lists('title', 'id'))
                ->render(),
            'modal_id' => self::MODAL_ID
        ]);
    }

    /**
     * Запись нового подрядчика в базу
     * POST /api/parts
     *
     * @return Response
     */
    public function store()
    {
        $data = [
            'title'      => Input::get('title'),
            'article'    => Input::get('article'),
            'unit'       => Input::get('unit'),
            'group_id'   => Input::get('group_id'),
            'unit_price' => Input::get('unit_price'),
        ];

        $validator = new PartValidator($data, 'create');

        if ( ! $validator->passes())
        {
            return $this->response->error(trans('calc::messages.fix_errors'))->data([
                'form_id' => self::FORM_ID,
                'errors'  => $validator->getErrors()
            ]);
        }

        // Create part
        $obj = $this->repository->create($data);

        if ( ! $obj)
        {
            return $this->response->message(
                trans('calc::part.create_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(trans('calc::part.created'));
    }

    /**
     * Получение подрядчика
     * GET /api/parts/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Форма редактирования подрядчика
     * GET /api/parts/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return $this->response->data([
            'modal' => view('calc::parts.modal')
                ->with('obj', $this->repository->find($id))
                ->with('groups', PartGroup::lists('title', 'id'))
                ->render(),
            'modal_id' => self::MODAL_ID,
            'groups'   => PartGroup::lists('id', 'title')
        ]);
    }

    /**
     * Сохранение подрядчика
     * PUT /api/parts/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $obj = $this->repository->find($id);

        $data = [
            'title'      => Input::get('title'),
            'article'    => Input::get('article'),
            'unit'       => Input::get('unit'),
            'group_id'   => Input::get('group_id'),
            'unit_price' => Input::get('unit_price'),
        ];

        $validator = new PartValidator($data, 'update');

        PartValidator::$rules['update']['article'][4] .= ',' . $obj->id;

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
                trans('calc::part.update_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(trans('calc::part.updated'));
    }

    /**
     * Удаление комплектующего
     * DELETE /api/parts/{id}
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var \Calc\Model\Part $obj */
        $obj = $this->repository->find($id);

        if ($obj->subjectElements()->count())
        {
            return $this->response->message(
                trans('calc::part.delete_has_elements', ['name' => $obj->title])
            );
        }

        if ( ! $obj->delete())
        {
            return $this->response->message(
                trans('calc::part.delete_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(
            trans('calc::part.deleted', ['name' => $obj->title])
        );
    }

    public function duplicate($id)
    {
        $obj = $this->repository->find($id);

        $attributes = array_except($obj->getAttributes(), [$obj->getKeyName()]);

        $instance = $this->repository->newEmpty();

        $instance->setRawAttributes($attributes);

        $instance->article = $attributes['article'];

        if ( ! $instance->save())
        {
            return $this->response->message(
                trans('calc::part.duplicate_error', ['name' => $obj->title])
            );
        }

        return $this->response->message(trans('calc::part.duplicated'));
    }
}
