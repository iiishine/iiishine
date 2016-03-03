<?php namespace Bigecko\Larapp\Validators;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator as LaravelValidator;


abstract class Validator
{
    public $rules = array();

    public $data;

    protected $_errors;

    protected $validator = null;

    public static function valid($data = null, $exceptionOnError = true)
    {
        $validator = new static($data);
        return $validator->validate($exceptionOnError);
    }

    public function __construct($data = null)
    {
        $this->data = $data ?: Input::all();
    }

    public function validate($exceptionOnError = true)
    {
        $validator = $this->getValidator();

        if ($validator->passes()) {
            return true;
        }

        $this->_errors = $validator->messages();

        if ($exceptionOnError) {
            throw new ValidationException('Validation error.', $validator);
        }

        return false;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function getValidator()
    {
        if (!$this->validator) {
            $this->validator = LaravelValidator::make($this->data, $this->rules);
        }

        return $this->validator;
    }
}
