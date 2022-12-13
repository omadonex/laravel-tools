<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxMethodNotImplementedInClassException extends \Exception
{
    protected $className;
    protected $methodName;

    public function __construct($className, $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;

        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("support::exceptions.{$exClassName}.message", [
            'method' => $methodName,
            'class' => $className,
        ]), ConstCustom::EXCEPTION_METHOD_NOT_IMPLEMENTED_IN_CLASS);
    }
}