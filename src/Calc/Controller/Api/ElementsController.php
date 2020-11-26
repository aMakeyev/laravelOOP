<?php namespace Calc\Controller\Api;

use Calc\Model\Element;
use Calc\Model\ElementCategory;

class ElementsController extends BaseController
{
    /**
     * Display a listing of the variables
     */
    public function index()
    {
        $repo = app('repo.elements');

        return $this->response->data([
            'data' => $repo->tree()
        ]);
    }

    public function category()
    {
        /** @var \Symfony\Component\HttpFoundation\ParameterBag $data */
        $data = request()->json();

        if ( ! is_object($data)) return $this->response->message('Ошибка');

        $data = $data->all();

        /** @var ElementCategory $category */
        $category = ! isset($data['id']) || ! $data['id']
            ? new ElementCategory
            : ElementCategory::find($data['id']);

        if ( ! $category) return $this->response->error('Категория не найдена');

        $category->title = sanitize($data['title']);
        $category->sort = (int) $data['sort'];
        $category->type = (int) $data['type'];
        $category->save();

        return $this->response->message("Категория \"{$category->title}\" сохранена");
    }

    public function element()
    {
        /** @var \Symfony\Component\HttpFoundation\ParameterBag $data */
        $data = request()->json();

        if ( ! is_object($data)) return $this->response->message('Ошибка');

        $data = $data->all();

        if ( ! isset($data['category_id']))
        {
            return $this->response->message('Не указана категория');
        }

        /** @var ElementCategory $category */
        $category = ElementCategory::find($data['category_id']);

        if ( ! $category) return $this->response->error('Категория не найдена');

        /** @var Element $element */
        $element = ! isset($data['id']) || ! $data['id']
            ? new Element
            : Element::find($data['id']);

        if ( ! $element) return $this->response->error('Элемент не найден');

        $element->title = sanitize($data['title']);
        $element->sort = (int) $data['sort'];
        $category->elements()->save($element);

        return $this->response->message("Элемент \"{$element->title}\" сохранен");
    }

    public function store()
    {
        $data = request()->json();
        $repo = app('repo.elements');

        foreach ($data as $cIdx => $c)
        {
            $category = isset($c['id']) && $c['id'] ? ElementCategory::findOrFail($c['id']) : new ElementCategory;
            $category->title = sanitize($c['title']);
            $category->sort = $cIdx + 1;
            $category->type = (int) $c['type'];
            $category->save();

            foreach ($c['elements'] as $eIdx => $e)
            {
                $element = isset($e['id']) && $e['id'] ? Element::findOrFail($e['id']) : new Element;
                $element->title = sanitize($e['title']);
                $element->sort = $eIdx + 1;
                $category->elements()->save($element);
            }
        }

        return $this->response->message('Все изменения сохранены')->data([
            'data' => $repo->tree()
        ]);
    }

    public function deleteCategory($id)
    {
        /** @var ElementCategory $category */
        $category = ElementCategory::find($id);

        if ( ! $category) return $this->response->error('Категория не найдена');

        if ($category->elements()->has('subjectElements')->count())
        {
            return $this->response->error('Нельзя удалить категорию т.к. с одним из её элементов связаны предметы расчета');
        }

        if ( ! $category->delete() )
        {
            return $this->response->error('Ошибка удаления категории или элементов');
        }

        $category->elements()->delete();

        return $this->response->message("Категория \"{$category->title}\" со всеми элементами удалена");
    }

    public function deleteElement($id)
    {
        /** @var Element $element */
        $element = Element::find($id);

        if ( ! $element) return $this->response->error('Элемент не найден');

        if ($element->subjectElements()->count())
        {
            return $this->response->error('Нельзя удалить элемент т.к. с ним связаны предметы расчета');
        }

        if ( ! $element->delete())
        {
            return $this->response->error('Ошибка удаления элемента');
        }

        return $this->response->message('Элемент удален');
    }

    public function sort()
    {
        $data = input();

        return $this->response->data([
            'data' => $data
        ]);
    }

}
