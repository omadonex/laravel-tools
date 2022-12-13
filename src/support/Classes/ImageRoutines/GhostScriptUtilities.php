<?php

namespace Omadonex\LaravelTools\Support\Classes\ImageRoutines;

use Illuminate\Support\Facades\Storage;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class GhostScriptUtilities
{
    /**
     * @return string
     */
    private static function getTempFolder()
    {
        $str = UtilsCustom::random_str(20);
        $folder = "temp/{$str}";
        Storage::disk('local')->makeDirectory($folder);

        return $folder;
    }

    /**
     * @param $contents
     * @param null $resolution
     * @return mixed
     * @throws \Omadonex\LaravelTools\Support\Classes\Exceptions\OmxShellException
     */
    public static function convertToJpg($contents, $resolution = null)
    {
        $folder = self::getTempFolder();
        $inputPath = storage_path("app/{$folder}/input");
        $outputPath = storage_path("app/{$folder}/output");
        Storage::disk('local')->put("{$folder}/input", $contents);

        GhostScriptProcessor::convertToJpg($inputPath, $outputPath, $resolution);
        $resultContents = Storage::disk('local')->get("{$folder}/output");
        Storage::disk('local')->deleteDirectory($folder);

        return $resultContents;
    }

    /**
     * @param $path
     * @param null $resolution
     * @return mixed
     * @throws \Omadonex\LaravelTools\Support\Classes\Exceptions\OmxShellException
     */
    public static function convertToJpgFromFile($path, $resolution = null)
    {
        $folder = self::getTempFolder();
        $outputPath = storage_path("app/{$folder}/output");
        GhostScriptProcessor::convertToJpg($path, $outputPath, $resolution);
        $resultContents = Storage::disk('local')->get("{$folder}/output");
        Storage::disk('local')->deleteDirectory($folder);

        return $resultContents;
    }
}