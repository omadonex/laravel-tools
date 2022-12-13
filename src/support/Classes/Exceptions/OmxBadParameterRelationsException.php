<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxBadParameterRelationsException extends \Exception
{
    protected $availableRelations;

    public function __construct($availableRelations)
    {
        $this->availableRelations = $availableRelations;

        $relationsStr = implode(", ", $availableRelations);
        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("support::exceptions.{$exClassName}.message", [
            'relations' => $relationsStr,
        ]), ConstCustom::EXCEPTION_BAD_PARAMETER_RELATIONS);
    }
}