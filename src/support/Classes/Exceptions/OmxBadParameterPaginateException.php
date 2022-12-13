<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxBadParameterPaginateException extends \Exception
{
    public function __construct()
    {
        $exClassName = UtilsCustom::getShortClassName($this);
        parent::__construct(trans("support::exceptions.{$exClassName}.message"), ConstCustom::EXCEPTION_BAD_PARAMETER_PAGINATE);
    }
}