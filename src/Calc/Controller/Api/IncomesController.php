<?php namespace Calc\Controller\Api;

use Input;
use Response;
use Calc\Model\Income;

class IncomesController extends BaseController
{
    /**
     * Создание оплаты
     *
     * @return \Calc\Helpers\Response
     */
    public function create()
    {
        /** @var array $data */
        $data = Input::all();

        if ( ! isset($data['subjects']) || ! is_array($data['subjects']) || ! $data['subjects'])
        {
            return $this->response->error('Добавьте хотя бы 1 предмет');
        }

        return $this->response->message('Расчет успешно сохранен');
    }

    /**
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Income $obj */
        $obj = Income::findOrFail($id);

        if ( ! $obj->delete())
        {
            return $this->response->error('Ошибка удаления оплаты');
        }

        return $this->response->message('Оплата удалена');
    }
}
