<?php namespace Calc\Validators;

use Illuminate\Support\MessageBag;
use Validator as IlluminateValidator;
use Input;

abstract class Validator
{
    protected $attributes;
    /** @var MessageBag */
    protected $errors;
    protected $level;
    public static $rules = [];

    public function __construct($data = null, $level = null)
    {
        $this->attributes = $data ?: Input::all();
        $this->level = $level;

        $this->registerValidatorRules();
    }

    protected function registerValidatorRules()
    {
        IlluminateValidator::extend('eng_rus_text', function ($attribute, $value, $parameters)
        {
            if (preg_match('/^[\-_\.\&\s\,\!]+/ui', $value))
            {
                return false;
            }

            if (preg_match('/[\-_\&\s\,]+$/ui', $value))
            {
                return false;
            }

            return preg_match('/^[\pL\pM\pN\_\-\s\.\&\,\!]+$/u', trim($value));
        });

        IlluminateValidator::extend('text', function ($attribute, $value, $parameters)
        {
            $value = sanitize($value);

            return ! empty($value);
        });
    }

    public function passes()
    {
        if ($this->level !== null)
        {
            $rules = static::$rules[$this->level];
        }
        else
        {
            $rules = static::$rules;

        }

        $messages = [];
        if (is_array(trans('calc::validation')))
        {
            $messages = trans('calc::validation');
        }

        $validation = IlluminateValidator::make($this->attributes, $rules, $messages);

        if ($validation->passes())
        {
            return true;
        }

        $this->errors = $validation->messages();

        return false;
    }

    public function getErrors($format = null)
    {
        if ($format)
        {
            return $this->errors->all($format);
        }

        return $this->errors->getMessages();
    }
}
