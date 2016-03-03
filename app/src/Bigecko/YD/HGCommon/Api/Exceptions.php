<?php namespace Bigecko\YD\HGCommon\Api;

// UAS Error exceptions
class UASServerErrorException extends \RuntimeException {}
class UASLoginErrorException extends \OutOfBoundsException {}

// CLS Error exceptions
class CLSErrorException extends \RuntimeException {}

// CPMS exceptions
class CPMSErrorException extends \Exception {}

// EMS exceptions
class EMSErrorException extends \Exception {}
