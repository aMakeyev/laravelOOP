<?php namespace Calc\Controller\Api;

use Input;
use Response;
use Calc\Model\Variable;

class VariablesController extends BaseController
{
    /**
     * Display a listing of the variables
     *
     * @return Response
     */
    public function index()
    {
        /** @var \Illuminate\Database\Query\Builder $q */
        $q = Variable::query();
        $sort = Input::get('sort', 'id');
        $q->orderBy(in_array($sort, Variable::$sortable) ? $sort : 'name', Input::get('order', 'asc'));

        return $this->response->data([
            'rows' => $q->get()->toArray()
        ]);
    }

    public function show($id)
    {
        return Variable::findOrFail($id);
    }

    public function store()
    {
        $obj = new Variable();
        $obj->name = Input::get('name');
        $obj->value = Input::get('value');
        $obj->title = Input::get('title');

        $obj->save();

        return $obj;
    }

    public function update($id)
    {
        /** @var Variable $obj */
        $obj = Variable::findOrFail($id);

        $obj->value = Input::get('value');

        if ( ! $obj->save())
        {
            $this->response->error("Ошибка сохранения переменной \"{$obj->title}\"");
        }

        return $this->response->message("Переменная \"{$obj->title}\" обновлена")->data([
            'value' => $obj->value
        ]);
    }
}
