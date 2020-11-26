<?php namespace Calc\Validators;

class SendmailValidator extends Validator
{
    public function __construct($data = null, $level = null)
    {
        parent::__construct($data, $level);

        static::$rules = require 'rules/sendmail.php';
    }
}
