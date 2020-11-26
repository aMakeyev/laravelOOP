<?php

namespace Calc\Helpers\Rus;

use Calc\Helpers\Rus\StringHelper;

/**
 * Преобразует цену в русский текст
 * Class Price
 * @package Calc\Helpers\Rus
 */
class Price
{
    protected $nul = 'ноль';

    protected $ten = [
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];

    protected $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];

    protected $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];

    protected $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

    protected $unit = [
        ['копейка', 'копейки', 'копеек', 1],
        ['рубль', 'рубля', 'рублей', 0],
        ['тысяча', 'тысячи', 'тысяч', 1],
        ['миллион', 'миллиона', 'миллионов', 0],
        ['миллиард', 'милиарда', 'миллиардов', 0],
    ];

    /**
     * Возвращает сумму прописью
     * @param int $num
     * @param bool $useUnit
     * @return string
     */
    public function getText($num, $useUnit = null)
    {
        list($rub, $kop) = explode('.', sprintf("%015.2f", (float)$num));

        $out = [];

        if ((int)$rub > 0) {
            foreach (str_split($rub, 3) as $uk => $v) {
                if (!(int)$v) {
                    continue;
                }

                $uk = count($this->unit) - $uk - 1;
                $gender = $this->unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));

                $out[] = $this->hundred[$i1];

                if ($i2 > 1) {
                    $out[] = $this->tens[$i2] . ' ' . $this->ten[$gender][$i3];
                } else {
                    $out[] = $i2 > 0 ? $this->a20[$i3] : $this->ten[$gender][$i3];
                }

                if ($uk > 1) {
                    $out[] = (new StringHelper())->morph($v, $this->unit[$uk][0], $this->unit[$uk][1], $this->unit[$uk][2]);
                }
            }
        } else {
            $out[] = $this->nul;
        }

        if ($useUnit) {
            $out[] = (new StringHelper())->morph((int)$rub, $this->unit[1][0], $this->unit[1][1], $this->unit[1][2]);
            $out[] = $kop . ' ' . (new StringHelper())->morph($kop, $this->unit[0][0], $this->unit[0][1], $this->unit[0][2]);
        }

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
    }

    /**
     * Возвращает отформатированную цену
     * @param $price
     * @return string
     */
    public function priceFormat($price)
    {
        return number_format($price, 0, '.', ' ');
    }
}
