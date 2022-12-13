<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;

class OmxModelCanNotBeDestroyedException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message, ConstCustom::EXCEPTION_MODEL_CAN_NOT_BE_DESTROYED);
    }
}