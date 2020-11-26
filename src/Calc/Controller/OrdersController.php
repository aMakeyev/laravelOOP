<?php namespace Calc\Controller;

use Calc\Model\Calculation;
use Calc\Core\Controllers\BaseController;
use Calc\Model\Order;
use Mail;

class OrdersController extends BaseController {
	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Calc\Repo\OrderRepo';
	/**
	 * @var \Calc\Repo\OrderRepo
	 */
	protected $repository;

	/**
	 * Список заказов / подрядов
	 */
	public function index() {
		$this->title->prepend(trans('calc::titles.orders'));
		$this->layout->content = view('calc::orders.index');
	}

	/**
	 * Вывод информации о заказе / подряде
	 *
	 * @param int $id ID Заказа
	 */
	public function show($id) {
		$obj = $this->repository->findWithAllRelations($id);

		$this->title->prepend(trans('calc::titles.orders'));
		$this->title->prepend($obj->title);
		$this->layout->content = view('calc::orders.show')->with('obj', $obj);
	}

	/**
	 * Вывод информации о заказе / подряде
	 *
	 * @param int $id ID Расчёта
	 */
	public function edit($id) {
		/** @var \Calc\Model\Calculation $obj */
		$obj = Calculation::with(['client', 'subjects', 'incomes' => function($q) {
			$q->orderBy('date', 'asc');
		}, 'orders.contractor', 'orders' => function($q) {
			$q->orderBy('subject_id', 'asc');
		}, 'orders.files', 'orders.contractorOutlay' => function($q) {
			$q->orderBy('date', 'asc');
		}, 'orders.constructorOutlay' => function($q) {
			$q->orderBy('date', 'asc');
		},])->findOrFail($id);

		//Формируем массив заказов и предметов для вывода несколких Подрядчиков на один предмет
		foreach($obj->orders as $order){
        $subs[$order->id] = $order->subject['id'];
    	}
        $temp = array_unique($subs);
		$ords = array_flip($temp);

		$this->title->prepend(trans('calc::titles.orders'));
		$this->title->prepend($obj->title);
		$this->layout->content = view('calc::orders.edit')->with(compact('obj', 'ords'));
	}
	/**
	 * Добавить к предмету подрядчика
	 *
	 * @param int $id ID Заказа
	 */
	public function addContractor($id){

		$order = Order::find($id);
		if (!$order)
			return redirect()->home()->with('status', 'Ошибка добавления подрядчика!');

		$newOrder = $order->replicate();
		$newOrder->contractor_id = '';
		$newOrder->description = '';
		$newOrder->status = 1;
		$newOrder->called_at = '';
		$newOrder->next_call_at = '';

		$newOrder->save();

		return redirect()->back()->with('status', 'Подрядчик успешно добавлен!');

	}
	/**
	 * Удаление Подрядчика (подряда)
	 * DELETE /orders/{id}
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		/** @var Order $obj */
		$obj = Order::find($id);

		$obj->forceDelete();

		return redirect()->back()->with('status', 'Подрядчик успешно удален!');

	}
	/**
	 * Присваивание заказу / подряду (всем предметам) статуса Сдано
	 *
	 * @param int $calculation_id ID Расчёта
	 */
	public function statusDone($calculation_id) {

		$orders = Order::query()->where('calculation_id', '=', $calculation_id)->update(['status' => 2]);

		return redirect('orders');

	}


	//Слияние расчётов - переделано на копирование предметов из одного расчёта в другой
	public function merge()
	{
		$subjects = Order::query()->where('calculation_id', '=', \Request::get('id_merged'))->with('elements')->get();
		if (!$subjects->first())
			return redirect()->back()->with('status', 'Ошибка! Расчёта # ' . \Request::get('id_merged') . ' не существует!');

		/** @var \Calc\Model\CalculationSubject $subject */
		foreach($subjects as $subject){

			//Делаем копию предмета
			$newSubject = $subject->replicate();
			$newSubject->calculation_id = \Request::get('id');
			$newSubject->save();
			/** @var \Calc\Model\SubjectElement $element */
			foreach ($subject->elements as $element) {
				/** @var \Calc\Model\SubjectElement $newElement */
				$newElement = $element->replicate();
				$newSubject->elements()->save($newElement);
			}

			//Присваиваем предмету id расчёта, в который переносим предмет
			//			$subject->calculation_id = \Request::get('id');
			//			$subject->save();
		}

		return redirect()->back()->with('status', 'Предметы успешно скопированы!');
	}
}