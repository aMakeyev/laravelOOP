<?php namespace Calc\Support;

use Config;

class PhoneFormat
{
    protected static $phoneCodes;

    public static function format($phone = '', $convert = true, $trim = true)
    {
        if ( ! isset(self::$phoneCodes))
        {
            self::$phoneCodes = Config::get('calc::phone_codes');
        }

        if (empty($phone))
        {
            return '';
        }
        // очистка от лишнего мусора с сохранением информации о "плюсе" в начале номера
        $phone = trim($phone);
        $plus = ($phone[0] == '+');
        $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);

        // конвертируем буквенный номер в цифровой
        if ($convert == true && ! is_numeric($phone))
        {
            $replace = ['2' => ['a', 'b', 'c'],
                        '3' => ['d', 'e', 'f'],
                        '4' => ['g', 'h', 'i'],
                        '5' => ['j', 'k', 'l'],
                        '6' => ['m', 'n', 'o'],
                        '7' => ['p', 'q', 'r', 's'],
                        '8' => ['t', 'u', 'v'],
                        '9' => ['w', 'x', 'y', 'z']];

            foreach ($replace as $digit => $letters)
            {
                $phone = str_ireplace($letters, $digit, $phone);
            }
        }

        // заменяем 00 в начале номера на +
        if (substr($phone, 0, 2) == "00")
        {
            $phone = substr($phone, 2, strlen($phone) - 2);
            $plus = true;
        }

        // если телефон длиннее 7 символов, начинаем поиск страны
        if (strlen($phone) > 7)
        {
            foreach (self::$phoneCodes as $countryCode => $data)
            {
                $codeLen = strlen($countryCode);
                if (substr($phone, 0, $codeLen) == $countryCode)
                {
                    // как только страна обнаружена, урезаем телефон до уровня кода города
                    $phone = substr($phone, $codeLen, strlen($phone) - $codeLen);
                    $zero = false;
                    // проверяем на наличие нулей в коде города
                    if ($data['zeroHack'] && $phone[0] == '0')
                    {
                        $zero = true;
                        $phone = substr($phone, 1, strlen($phone) - 1);
                    }

                    $cityCode = null;
                    // сначала сравниваем с городами-исключениями
                    if ($data['exceptions_max'] != 0)
                        for ($cityCodeLen = $data['exceptions_max']; $cityCodeLen >= $data['exceptions_min']; $cityCodeLen--)
                            if (in_array(intval(substr($phone, 0, $cityCodeLen)), $data['exceptions']))
                            {
                                $cityCode = ($zero ? "0" : "") . substr($phone, 0, $cityCodeLen);
                                $phone = substr($phone, $cityCodeLen, strlen($phone) - $cityCodeLen);
                                break;
                            }
                    // в случае неудачи с исключениями вырезаем код города в соответствии с длиной по умолчанию
                    if (is_null($cityCode))
                    {
                        $cityCode = substr($phone, 0, $data['cityCodeLength']);
                        $phone = substr($phone, $data['cityCodeLength'], strlen($phone) - $data['cityCodeLength']);
                    }

                    // возвращаем результат
                    return ($plus ? "+" : "") . $countryCode . '(' . $cityCode . ')' . self::phoneBlocks($phone);
                }
            }
        }

        // возвращаем результат без кода страны и города
        return ($plus ? "+" : "") . self::phoneBlocks($phone);
    }

    /**
     * Функция превращает любое числов в строку формата XX-XX-... или XXX-XX-XX-...
     * в зависимости от четности кол-ва цифр
     *
     * @param $number
     *
     * @return string
     */
    protected static function phoneBlocks($number)
    {
        $add = '';
        if (strlen($number) % 2)
        {
            $add = $number[0];
            $number = substr($number, 1, strlen($number) - 1);
        }

        return $add . implode("-", str_split($number, 2));
    }
}
