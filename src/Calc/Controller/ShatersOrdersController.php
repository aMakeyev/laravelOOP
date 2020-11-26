<?php namespace Calc\Controller;

use Calc\Model\ShatersCalculation;
use Calc\Core\Controllers\BaseController;
use Calc\Model\ShatersOrder;
use Mail;

class ShatersOrdersController extends BaseController {

	protected $layout = 'calc::shaters_layout';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Calc\Repo\ShatersOrderRepo';
	/**
	 * @var \Calc\Repo\ShatersOrderRepo
	 */
	protected $repository;

	/**
	 * Список заказов / подрядов
	 */
	public function index() {
		$this->title->prepend(trans('calc::titles.orders'));
		$this->layout->content = view('calc::shaters.orders.index');
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
		$this->layout->content = view('calc::shaters.orders.show')->with('obj', $obj);
	}

	/**
	 * Вывод информации о заказе / подряде
	 *
	 * @param int $id ID Расчёта
	 */
	public function edit($id) {
		/** @var \Calc\Model\ShatersCalculation $obj */
		$obj = ShatersCalculation::with(['client', 'subjects', 'incomes' => function($q) {
			$q->orderBy('date', 'asc');
		}, 'orders.contractor', 'orders' => function($q) {
			$q->orderBy('subject_id', 'asc');
		}, 'orders.files',
			'orders.contractorOutlay' => function($q) {
			$q->orderBy('date', 'asc');
		},
			'orders.constructorOutlay' => function($q) {
			$q->orderBy('date', 'asc');
		},
			])->findOrFail($id);

		//Формируем массив заказов и предметов для вывода несколких Подрядчиков на один предмет
		foreach($obj->orders as $order){
        $subs[$order->id] = $order->subject['id'];
    	}
        $temp = array_unique($subs);
		$ords = array_flip($temp);

		$this->title->prepend(trans('calc::titles.orders'));
		$this->title->prepend($obj->title);
		$this->layout->content = view('calc::shaters.orders.edit')->with(compact('obj', 'ords'));
	}
	/**
	 * Добавить к предмету подрядчика
	 *
	 * @param int $id ID Заказа
	 */
	public function addContractor($id){

		$order = ShatersOrder::find($id);
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
		/** @var ShatersOrder $obj */
		$obj = ShatersOrder::find($id);

		$obj->forceDelete();

		return redirect()->back()->with('status', 'Подрядчик успешно удален!');

	}
	/**
	 * Присваивание заказу / подряду (всем предметам) статуса Сдано
	 *
	 * @param int $calculation_id ID Расчёта
	 */
	public function statusDone($calculation_id) {

		$orders = ShatersOrder::query()->where('calculation_id', '=', $calculation_id)->update(['status' => 2]);

		return redirect('shaters/orders');

	}


	//Слияние расчётов - переделано на копирование предметов из одного расчёта в другой
	public function merge()
	{
		$subjects = ShatersOrder::query()->where('calculation_id', '=', \Request::get('id_merged'))->with('elements')->get();
		if (!$subjects->first())
			return redirect()->back()->with('status', 'Ошибка! Расчёта # ' . \Request::get('id_merged') . ' не существует!');

		/** @var \Calc\Model\ShatersCalculationSubject $subject */
		foreach($subjects as $subject){

			//Делаем копию предмета
			$newSubject = $subject->replicate();
			$newSubject->calculation_id = \Request::get('id');
			$newSubject->save();
			/** @var \Calc\Model\ShatersSubjectElement $element */
			foreach ($subject->elements as $element) {
				/** @var \Calc\Model\ShatersSubjectElement $newElement */
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