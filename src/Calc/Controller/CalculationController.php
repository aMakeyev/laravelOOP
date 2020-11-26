<?php namespace Calc\Controller;

use Auth;
use Calc\Model\CalculationSubject;
use Response;
use Calc\Helpers\Elements;
use Calc\Model\Calculation;
use Calc\Model\Variable;
use Calc\Core\Controllers\BaseController;

class CalculationController extends BaseController
{
    public function index()
    {
        $this->title->prepend(trans('calc::titles.calculations'));
        $this->layout->content = view('calc::calculations.index')->with([
            'discount' => Variable::find('discount')
        ]);
    }

    public function create()
    {
        $this->title->prepend(trans('calc::titles.calculation_create'));
        $this->layout->content = view('calc::calculations.create')
            ->with('obj', new Calculation)
            ->with('elements', new Elements);
    }

    public function edit($id)
    {
        /** @var Calculation $obj */
        $obj = Calculation::with([
            'client',
            'subjects.elements.part',
            'subjects.constructorRate',
            'files'
        ])->findOrFail($id);

        $this->title->prepend(trans('calc::titles.calculation_edit'));
        $this->layout->content = view('calc::calculations.create')
            ->with('obj', $obj)
            ->with('elements', new Elements);

        return Response::make($this->layout, 200, [
            'Pragma'        => 'no-cache',
            'Expires'       => 'Thu, 19 Nov 1981 08:52:00 GMT',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
        ]);
    }

    public function show($id)
    {
        $obj = Calculation::findOrFail($id);

        $this->title->prepend(trans('calc::titles.calculation'));
        $this->title->prepend($obj->title);
        $this->layout->content = view('calc::calculations.show')->with('obj', $obj);
    }

    //Слияние расчётов - переделано на копирование предметов из одного расчёта в другой
	public function merge()
	{
		$subjects = CalculationSubject::query()->where('calculation_id', '=', \Request::get('id_merged'))->with('elements')->get();
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

	//Создание Доп.соглашения

	public function addagree($id)
	{
		/** @var Calculation $obj */
		$obj = Calculation::findOrFail($id);

		/** @var Calculation $addagree */
		$addagree = new Calculation();

		$addagree->parent_id = $obj->id;
		$addagree->title = 'Доп. соглашение к расчёту #' . $obj->id;
		$addagree->user_id = Auth::user()->id;
		$addagree->client_id = $obj->client_id;
		$addagree->status = 1;
		$addagree->additional_coefficient = 8;
		$addagree->pseudo_discount_percent = 0;
		$addagree->pseudo_discount_meter = 0;

		if (!$addagree->save()) {
			return false;
		}
		$obj->child_id = $addagree->id;
		if (!$obj->save()) {
			return false;
		}
		return $this->edit($addagree->id);
	}
}
