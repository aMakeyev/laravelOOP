<?php

use Calc\Model\Element;
use Calc\Model\ElementCategory;

class ElementsSeeder extends Seeder {

    public function run()
    {
        foreach ($this->getElementsCategories() as $s => $c)
        {
            $category = new ElementCategory();
            $category->title = $c['title'];
            $category->type = $c['type'];
            $category->sort = $s;
            $category->save();

            foreach ($c['elements'] as $sort => $e)
            {
                $element = new Element();
                $element->title = $e;
                $element->sort = $sort;
                $category->elements()->save($element);
            }
        }
    }

    private function getElementsCategories()
    {
        return [
            [
                'title' => 'Фасадная часть',
                'type' => 1, // фасад
                'elements' => [
                    'Фасад ящика',
                    'Фасад дверей',
                    'Фальш панель'
                ]
            ],
            [
                'title' => 'Каркасная часть',
                'type' => 2, // каркас
                'elements' => [
                    'Правый бок',
                    'Левый бок',
                    'Внутренняя перегородка',
                    'Перегородки ящиков',
                    'Полка',
                    'Дно',
                    'Верх/крышка',
                    'Столешница',
                ]
            ],
            [
                'title' => 'Лицевая фурнитура',
                'type' => 3, // фурнитура
                'elements' => [
                    'Ручки',
                    'Ножки',
                    'Светильник',
                    'Багет'
                ]
            ],
            [
                'title' => 'Крепёжная фурнитура',
                'type' => 3, // фурнитура
                'elements' => [
                    'Комплект фурнитуры стяжной',
                    'Петли',
                    'Корзина для белья',
                    'Направляющие для ящиков',
                ]
            ]
        ];
    }

}
