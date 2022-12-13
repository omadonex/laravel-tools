<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxModelProtectedException extends \Exception
{
    protected $model;

    public function __construct($model)
    {
        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("support::exceptions.{$exClassName}.message", [
            'class' => get_class($model),
        ]), ConstCustom::EXCEPTION_MODEL_PROTECTED);
    }
}