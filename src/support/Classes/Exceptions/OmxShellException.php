<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxShellException extends \Exception
{
    protected $result;
    protected $output;

    public function __construct($result, $output)
    {
        $this->result = $result;
        $this->output = $output;

        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("support::exceptions.{$exClassName}.message", [
            'result' => $result,
        ]), ConstCustom::EXCEPTION_SHELL);
    }
}