<?php

namespace Omadonex\LaravelTools\Support\Interfaces\FileStorage;

interface IFileStorageAccessible
{
    public function getFileMeta($params = []);
}