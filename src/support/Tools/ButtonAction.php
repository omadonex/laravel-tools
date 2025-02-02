<?php

namespace Omadonex\LaravelTools\Support\Tools;

class ButtonAction
{
    const BACK = 'back';
    const CANCEL = 'cancel';
    const CLEAR = 'clear';
    const COMMENT = 'comment';
    const CREATE = 'create';
    const DELETE = 'delete';
    const DOWNLOAD = 'download';
    const EDIT = 'edit';
    const EXPORT = 'export';
    const IMPORT = 'import';
    const STEP_NEXT = 'stepNext';
    const STEP_PREVIOUS = 'stepPrevious';
    const REPLY = 'reply';
    const SAVE = 'save';
    const SEND = 'send';
    const UPLOAD = 'upload';
    const VIEW = 'view';

    protected static function map(): array
    {
        return [
            self::BACK => [
                'text' => 'Назад',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.bold.move-back',
            ],
            self::CANCEL => [
                'text' => 'Отмена',
                'context' => Context::SECONDARY,
            ],
            self::CLEAR => [
                'text' => 'Очистить',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.regular.close',
            ],
            self::COMMENT => [
                'text' => 'Комментировать',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.regular.message-bouble',
            ],
            self::CREATE => [
                'text' => 'Создать',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.regular.add-bold',
            ],
            self::DELETE => [
                'text' => 'Удалить',
                'context' => Context::DANGER,
                'icon' => 'streamline.regular.close',
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
            self::STEP_NEXT => [
                'text' => 'Вперед',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.regular.arrow-thick-right',
            ],
            self::STEP_PREVIOUS => [
                'text' => 'Назад',
                'context' => Context::SECONDARY,
                'icon' => 'streamline.regular.arrow-thick-left',
            ],
            self::REPLY => [
                'text' => 'Ответить',
                'context' => Context::INFO,
                'icon' => 'streamline.regular.message-bouble-double',
            ],
            self::SAVE => [
                'text' => 'Сохранить',
                'context' => Context::SUCCESS,
                'icon' => 'streamline.regular.floppy-disk',
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
