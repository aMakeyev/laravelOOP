<?php namespace Calc\Controller\Api;

use Calc\Model\CalculationSubject;
use Calc\Model\SubjectElement;

class SubjectsController extends BaseController
{
    public function destroySubject($id)
    {
        $obj = CalculationSubject::findOrFail($id);

        if ( ! $obj->delete())
        {
            return $this->response->error('Ошибка удаление предмета');
        }

        return $this->response->message('Предмет удален');
    }

    public function destroyElement($id)
    {
        $obj = SubjectElement::findOrFail($id);

        if ( ! $obj->delete())
        {
            return $this->response->error('Ошибка удаление элемента');
        }

        return $this->response->message('Элемент удален');
    }
}
