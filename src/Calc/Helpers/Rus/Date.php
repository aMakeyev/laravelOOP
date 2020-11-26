<?php
namespace Calc\Helpers\Rus;

/**
 * Работа с датами - нужно расширить
 * Class Date
 * @package Calc\Helpers
 */
class Date
{
    /**
     *  Именительный падеж
     */
    const CASE_IMENITELNIY = 'I';

    /**
     * Родительный падеж
     */
    const CASE_RODITELNIY = 'R';

    protected $names = [
        1 => [
            'I' => 'январь',
            'R' => 'января',
        ],
        2 => [
            'R' => 'февраля',
        ],
        3 => [
            'R' => 'марта',
        ],
        4 => [
            'R' => 'апреля',
        ],
        5 => [
            'R' => 'мая',
        ],
        6 => [
            'R' => 'июня',
        ],
        7 => [
            'R' => 'июля',
        ],
        8 => [
            'R' => 'августа',
        ],
        9 => [
            'R' => 'сентября',
        ],
        10 => [
            'R' => 'октября',
        ],
        11 => [
            'R' => 'ноября',
        ],
        12 => [
            'R' => 'декабря',
        ],
    ];

    /**
     * Возвращает русское название месяца с нужным падежом
     * @param int $monthNum
     * @param string $case
     * @return string
     */
    public function getRusMoth($monthNum, $case = null)
    {
        $monthNum = (int)$monthNum;

        if (!$case)
            $case = self::CASE_IMENITELNIY;

        return $this->names[$monthNum][$case];
    }
}
