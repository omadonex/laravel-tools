<?php

namespace Omadonex\LaravelTools\Support\Classes\Utils;

use Illuminate\Database\Schema\Blueprint;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;

class UtilsDb
{
    public static function addPrimaryStr($table)
    {
        $table->string('id', ConstCustom::DB_FIELD_LEN_PRIMARY_STR);
        $table->primary('id');
    }

    public static function addTransFields($table, $stringModelId = false)
    {
        $fieldName = ConstCustom::DB_FIELD_TRANS_MODEL_ID;
        if ($stringModelId) {
            $table->string($fieldName, ConstCustom::DB_FIELD_LEN_PRIMARY_STR)->index();
        } else {
            $table->unsignedInteger($fieldName)->index();
        }

        $table->string(ConstCustom::DB_FIELD_TRANS_LANG, ConstCustom::DB_FIELD_LEN_LANG)->index();

        $table->unique([$fieldName, ConstCustom::DB_FIELD_TRANS_LANG], "{$table->getTable()}_trans_unique");
    }

    public static function addUnsafeSeedingField($table)
    {
        $table->boolean(ConstCustom::DB_FIELD_UNSAFE_SEEDING)->default(false)->index();
    }

    public static function addProtectedGenerateField($table)
    {
        $table->boolean(ConstCustom::DB_FIELD_PROTECTED_GENERATE)->default(false)->index();
    }

    public static function historyFieldsCallback(bool $stringModelId = false)
    {
        return function (Blueprint $table) use ($stringModelId) {
            $table->id();
            if ($stringModelId) {
                $table->string('model_id', ConstCustom::DB_FIELD_LEN_PRIMARY_STR)->index();
            } else {
                $table->unsignedBigInteger('model_id')->index();
            }
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedTinyInteger('history_event_id')->index();
            $table->timestamp('occur_at');
            $table->text('data');
        };
    }
}