<?php namespace Bigecko\Larapp\Validators;

class ValidationException extends \RuntimeException
{
    protected $validator;

    public function __construct($message = '', $validator = null)
    {
        parent::__construct($message);

        $this->validator = $validator;
    }

    public function errors()
    {
        return $this->validator->errors();
    }
}

