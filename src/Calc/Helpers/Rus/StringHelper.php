<?php

namespace Calc\Helpers\Rus;

/**
 * Работа со строками
 * Class StringHelper
 * @package Calc\Helpers\Rus
 */
class StringHelper
{
    /**
     * Склоняем словоформу
     * @param int $n
     * @param string $f1
     * @param string $f2
     * @param string $f5
     * @return string
     */
    public function morph($n, $f1, $f2, $f5)
    {
        $n = abs((int)$n) % 100;

        if ($n > 10 && $n < 20) {
            return $f5;
        }

        $n %= 10;

        if ($n > 1 && $n < 5) {
            return $f2;
        }

        if ($n === 1) {
            return $f1;
        }

        return $f5;
    }

    /**
     * Делает строку с заглавной буквы
     * @param $string $string
     * @return string
     */
    public function firstUpper($string)
    {
        $char = mb_strtoupper(substr($string, 0, 2), "utf-8"); // это первый символ
        $string[0] = $char[0];
        $string[1] = $char[1];

        return $string;
    }
}