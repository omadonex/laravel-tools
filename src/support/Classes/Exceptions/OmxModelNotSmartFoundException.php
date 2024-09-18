<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class OmxModelNotSmartFoundException extends \Exception
{
    protected $model;
    protected $value;
    protected $field;

    public function __construct($model, $value, $field)
    {
        $this->model = $model;
        $this->value = $value;
        $this->field = $field;

        $exClassName = UtilsCustom::getShortClassName($this);;
        parent::__construct(trans("omx-support::exceptions.{$exClassName}.message", [
            'table' => $model->getTable(),
            'field' => $field,
            'value' => $value,
            'class' => get_class($model),
        ]), ConstCustom::EXCEPTION_MODEL_NOT_SMART_FOUND);
    }
}