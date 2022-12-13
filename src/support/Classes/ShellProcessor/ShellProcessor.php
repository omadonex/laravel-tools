<?php

namespace Omadonex\LaravelTools\Support\Classes\ShellProcessor;

use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxShellException;

class ShellProcessor
{
    protected static function call($command)
    {
        exec($command, $output, $result);

        if ($result !== 0) {
            throw new OmxShellException($result, $output);
        }

        return $output;
    }
}