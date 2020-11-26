<?php namespace Calc\Controller\Api;

use Calc\Model\ShatersElement;
use Calc\Model\ShatersElementCategory;

class ShatersElementsController extends BaseController
{
    /**
     * Display a listing of the variables
     */
    public function index()
    {
        $repo = app('repo.shaters_elements');

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

        /** @var ShatersElementCategory $category */
        $category = ! isset($data['id']) || ! $data['id']
            ? new ShatersElementCategory
            : ShatersElementCategory::find($data['id']);

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

        /** @var ShatersElementCategory $category */
        $category = ShatersElementCategory::find($data['category_id']);

        if ( ! $category) return $this->response->error('Категория не найдена');

        /** @var ShatersElement $element */
        $element = ! isset($data['id']) || ! $data['id']
            ? new ShatersElement
            : ShatersElement::find($data['id']);

        if ( ! $element) return $this->response->error('Элемент не найден');

        $element->title = sanitize($data['title']);
        $element->sort = (int) $data['sort'];
        $category->elements()->save($element);

        return $this->response->message("Элемент \"{$element->title}\" сохранен");
    }

    public function store()
    {
        $data = request()->json();
        $repo = app('repo.shaters_elements');

        foreach ($data as $cIdx => $c)
        {
            $category = isset($c['id']) && $c['id'] ? ShatersElementCategory::findOrFail($c['id']) : new ShatersElementCategory;
            $category->title = sanitize($c['title']);
            $category->sort = $cIdx + 1;
            $category->type = (int) $c['type'];
            $category->save();

            foreach ($c['elements'] as $eIdx => $e)
            {
                $element = isset($e['id']) && $e['id'] ? ShatersElement::findOrFail($e['id']) : new ShatersElement;
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
        /** @var ShatersElementCategory $category */
        $category = ShatersElementCategory::find($id);

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
        /** @var ShatersElement $element */
        $element = ShatersElement::find($id);

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
