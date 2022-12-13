<?php

namespace Omadonex\LaravelTools\Acl\Exceptions;

class OmxUserResourceClassNotSetException extends \Exception
{
    public function __construct()
    {
        $className = get_class($this);
        parent::__construct(trans("acl::exception.{$className}.message"));
    }
}