<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxClassNotUsesTraitException extends \Exception
{
    protected $className;
    protected $traitName;

    public function __construct($className, $traitName)
    {
        $this->className = $className;
        $this->traitName = $traitName;

        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("support::exceptions.{$exClassName}.message", [
            'class' => $className,
            'trait' => $traitName,
        ]), ConstCustom::EXCEPTION_CLASS_NOT_USES_TRAIT);
    }
}