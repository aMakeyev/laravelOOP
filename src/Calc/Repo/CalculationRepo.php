<?php namespace Calc\Repo;

use Calc\Model\Calculation;
use Calc\Model\CalculationWrapper;
use Calc\Model\Order;
use Calc\Model\SubjectWrapper;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalculationRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\Calculation';

    /**
     * Find own record by ID
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return \Calc\Model\Calculation
     */
    public function findOwn($id, $columns = ['*'])
    {
        return $this->model->own()->findOrFail($id, $columns);
    }

    /**
     * Получение расчета по ID со всеми связями
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|Calculation
     */
    public function findWithAllRelations($id)
    {
        $obj = $this->model->query()->with([
            'subjects.elements.part',
            'subjects.elements.element.category',
            'subjects.constructorRate',
            'additionalCoefficient',
            'manager',
            'client',
        ])->findOrFail($id);

        return $obj;
    }

    /**
     * Получение расчета по ID с предметами и элементами
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|Calculation
     */
    public function findWithSubjects($id)
    {
        $obj = $this->model->query()->with([
            'subjects.elements',
        ])->findOrFail($id);

        return $obj;
    }

    /**
     * Create calculation
     *
     * @param array $attributes
     *
     * @return Calculation|static
     */
    public function create(array $attributes)
    {
        /** @var Calculation $obj */
        $obj = $this->model->newInstance($attributes);

        return $obj->save() ? $obj : null;
    }

    /**
     * Update calculation with relations
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function updateWithRelations($id, array $attributes)
    {
        /** @var Calculation $obj */
        $obj = $this->findWithSubjects($id);

        $obj->fill($attributes);

        return $obj->save() ? $obj : null;
    }

    /**
     * @param array $subjects
     *
     * @return array
     */
    public function generateSubjectsMap(array $subjects)
    {
        $subjectsMap = [
            'update' => [],
            'create' => [],
        ];

        $elementsMap = [
            'update' => [],
            'create' => [],
        ];

        // Составляем карту предметов
        foreach ($subjects as $s) {
            // Проверяем наличие ID у предмета
            if (isset($s['id'])) {
                if ($s['id'] > 0) {
                    // Предметы для обновления
                    $subjectsMap['update'][$s['id']] = $s;

                    foreach ($s['elements'] as $e) {
                        if ($e['id'] > 0) {
                            // Элементы предмета для обновления
                            $elementsMap['update'][$s['id']][$e['id']] = $e;
                        } elseif ($e['id'] == 0) {
                            // Элементы предмета для создания
                            $elementsMap['create'][$s['id']][] = $e;
                        }
                    }
                } elseif ($s['id'] == 0) {
                    // Предметы для создания
                    $subjectsMap['create'][] = $s;
                }
            }
        }

        return [$subjectsMap, $elementsMap];
    }

    public function duplicate($id)
    {
        /** @var Calculation $obj */
        $obj = $this->model->query()->with('subjects.elements')->findOrFail($id);

        /** @var Calculation $newObj */
        $newObj = $obj->replicate();

        $newObj->title .= ' - Копия';

        // Ставим статус Черновик
        $newObj->status = 1;

        if (!$newObj->save()) {
            return false;
        }

        /** @var \Calc\Model\CalculationSubject $subject */
        foreach ($obj->subjects as $subject) {
            /** @var \Calc\Model\CalculationSubject $newSubject */
            $newSubject = $subject->replicate();
            $newObj->subjects()->save($newSubject);

            /** @var \Calc\Model\SubjectElement $element */
            foreach ($subject->elements as $element) {
                /** @var \Calc\Model\SubjectElement $newElement */
                $newElement = $element->replicate();
                $newSubject->elements()->save($newElement);
            }
        }

        return $newObj;
    }

    /**
     * Формирование расчета для клиента
     *
     * @param $id
     *
     * @return array
     */
    public function calculateForClient($id)
    {
        /** @var Calculation $order */
        $order = $this->findWithAllRelations($id);

        if ($order->status < 2) {
            throw new NotFoundHttpException;
        }
        if($order->parent_id){
			/** @var Calculation $parentOrder */
			$parentOrder = Calculation::findOrFail($order->parent_id);
		}

        $subjects = [];
        $costs = new CalculationWrapper($order);

        $coeff = $order->additionalCoefficient->value;

        $subjectsCount = count($order->subjects);

        foreach ($order->subjects as $s) {
            $subject = new SubjectWrapper($s);
            $subjects[] = $subject;

            $subject->construct_assembly = $coeff * ($subject->cost_construct + $subject->cost_assembly) * $s->num;

            foreach ($s->elements as $e) {
                if ($e->element->category->isFacade()) {
                    $subject->facade += $s->num * $coeff * $e->sum;
                } elseif ($e->element->category->isSkeleton($e->character)) {
                    $subject->skeleton += $s->num * $coeff * $e->sum;
                } elseif ($e->element->category->isFurniture($e->character)) {
                    $subject->furniture += $s->num * $coeff * $e->sum;
                }
            }

            $subject->total =
                $subject->facade +
                $subject->skeleton +
                $subject->furniture +
                $subject->construct_assembly;

            // Коэффициет наценки / скидки

            $k = (1 + $subject->discount / $subject->total);

			$subject->facade *= $k;
			$subject->skeleton *= $k;
			$subject->furniture *= $k;
			$subject->construct_assembly *= $k;

			$subject->total =
				$subject->facade +
				$subject->skeleton +
				$subject->furniture +
				$subject->construct_assembly;

			//Псевдо скидка за замер прибавляется только к первому предмету
			if($subject->i == 1){
				$k = (1 + $order->pseudo_discount_meter / $subject->total);
			} else{
				$k = 1;
			}
			//Псевдо скидка %
            $pseudoK = ((100 + $order->pseudo_discount_percent) / 100);
            $k = $k * $pseudoK;

            $subject->facade *= $k;
            $subject->skeleton *= $k;
            $subject->furniture *= $k;
            $subject->construct_assembly *= $k;


            $subject->total =
                $subject->facade +
                $subject->skeleton +
                $subject->furniture +
                $subject->construct_assembly;


            $subject->totalDiscount = $subject->total / $pseudoK;

            $costs->num += $s->num;

            $costs->total += $subject->total;
            $costs->totalDiscount += $subject->total / $pseudoK;
        }

        $costs->totalDiscount -= $order->pseudo_discount_meter;

        $costs->pseudo_discount_meter = -$order->pseudo_discount_meter;
        $costs->pseudo_discount_percent_value = $costs->totalDiscount - $costs->total;

        return compact('order', 'subjects', 'costs', 'parentOrder');
    }

    /**
     * Предмет является фасадной частью
     *
     * @param $id
     *
     * @return bool
     */
    protected function isFacade($id)
    {
        return $id > 0 && $id < 20;
    }

    /**
     * Предмет является внутр. элем., каркасной частью или крепежем
     *
     * @param $id
     *
     * @return bool
     */
    protected function isSkeleton($id)
    {
        return ($id >= 20 && $id < 60); // || ($id >= 80);
    }

    /**
     * Предмет является фурнитурой
     *
     * @param $id
     *
     * @return bool
     */
    protected function isFurniture($id)
    {
        return $id >= 60 && $id < 100;
    }

    /**
     * Генерация заказов / подрядов из расчета
     *
     * @param int $id ID расчета
     *
     * @return bool
     */
    public function makeOrders($id)
    {
        /** @var Calculation $obj */
        $obj = $this->findWithSubjects($id);

        // Статус при котором формируем заказы
        if ($obj::MAKE_ORDERS_STATUS != $obj->status) {
            return false;
        }

        /** @var \Calc\Model\CalculationSubject $subject */
        foreach ($obj->subjects as $subject) {
            // Создаем кол-во заказов / подрядов на каждый предмет
            $order = new Order;

            $order->cost = $subject->cost_total * $subject->num;
            $order->subject = $subject;

            $obj->orders()->save($order);
        }

        return true;
    }

    public function paginate(array $data = [])
    {
        /** @var Builder|\Illuminate\Database\Query\Builder $q */
        $q = $this->query()->with(['manager.countCalculations', 'client']);

        $sort = array_get($data, 'sort');

        switch ($sort) {
            case 'users.last_name':
                $q->leftJoin('users', 'users.id', '=', 'calculations.user_id');
                break;
            case 'clients.last_name':
                $q->leftJoin('clients', 'clients.id', '=', 'calculations.client_id');
                break;
        }

        $q->sort($sort, array_get($data, 'order'));
        $q->status(array_get($data, 'status'));
        $q->search(array_get($data, 'term'));
        $q->manager(array_get($data, 'manager'));
        $q->own();

        $paginator = $q->paginate((int)array_get($data, 'rows'));

        return $paginator;
    }
}
