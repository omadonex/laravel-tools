<?php

namespace Omadonex\LaravelTools\Support\Tools;

class ButtonAction
{
    const CANCEL = 'cancel';
    const CLEAR = 'clear';
    const CREATE = 'create';
    const DOWNLOAD = 'download';
    const EDIT = 'edit';
    const EXPORT = 'export';
    const IMPORT = 'import';
    const SAVE = 'save';
    const SEND = 'send';
    const UPLOAD = 'upload';
    const VIEW = 'view';

    protected static function map(): array
    {
        return [
            self::CANCEL => [
                'text' => 'Отмена',
                'context' => Context::SECONDARY,
            ],
            self::CLEAR => [
                'text' => 'Очистить',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.regular.close',
            ],
            self::CREATE => [
                'text' => 'Создать',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.bold.add-bold',
            ],
            self::DOWNLOAD => [
                'text' => 'Скачать',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.bold.move-down',
            ],
            self::EDIT => [
                'text' => 'Редактировать',
                'context' => Context::WARNING,
                'icon' => 'streamline.regular.edit',
            ],
            self::EXPORT => [
                'text' => 'Экспорт',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.bold.microsoft-excel',
            ],
            self::IMPORT => [
                'text' => 'Импорт',
                'context' => Context::INFO,
                'icon' => 'streamline.bold.move-up',
            ],
            self::SAVE => [
                'text' => 'Сохранить',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.bold.floppy-disk',
            ],
            self::SEND => [
                'text' => 'Отправить',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.regular.send-email',
            ],
            self::UPLOAD => [
                'text' => 'Загрузить',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.bold.move-up',
            ],
            self::VIEW => [
                'text' => 'Просмотр',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.bold.view',
            ],
        ];
    }

    public static function data(string $action): array
    {
        return static::map()[$action] ?? [];
    }
}
