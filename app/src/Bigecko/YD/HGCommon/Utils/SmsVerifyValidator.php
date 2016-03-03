<?php namespace Bigecko\YD\HGCommon\Utils;

use Bigecko\Larapp\Validation\Validator;

use Illuminate\Support\Facades\Validator as LaravelValidator;

class SmsVerifyValidator extends Validator
{
    public $rules = array(
        'phone' => 'required|regex:/^1\d{10}$/',
        'sms_code' => 'required|sms_verify',
    );

    public function __construct($input = null, $verifier = null)
    {
        parent::__construct($input);

        $inputData = $this->input;
        LaravelValidator::extend('sms_verify', function($attribute, $value, $parameters) use ($verifier, $inputData) {
            return $verifier->checkCode($inputData['phone'], $value);
        });
    }
}

