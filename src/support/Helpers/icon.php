<?php

use Omadonex\LaravelTools\Support\Tools\Color;

if (!function_exists('rowEditIcon')) {
    function rowEditIcon($id, string $caption = '', string $hint = '', string $href = '', $path = ''): string
    {
        if (!$hint) {
            $hint = 'Редактировать';
        }
        $icon = getIconHtml('streamline.regular.edit', 16, Color::WARNING, path: $path);

        if ($href) {
            return "<span><a title='{$hint}' href='{$href}' style='cursor: pointer;'>{$icon}</a></span>";
        }

        return "<span data-row-id='{$id}' data-row-caption='{$caption}' title='{$hint}' class='js-row-edit' style='cursor: pointer;'>{$icon}</span>";
    }
}

if (!function_exists('rowViewIcon')) {
    function rowViewIcon(string $href, string $hint = '', $path = ''): string
    {
        if (!$hint) {
            $hint = 'Просмотр';
        }
        $icon = getIconHtml('streamline.regular.view', 16, Color::INFO, path: $path);

        return "<span><a title='{$hint}' href='{$href}' style='cursor: pointer;'>{$icon}</a></span>";
    }
}

if (!function_exists('rowHistoryIcon')) {
    function rowHistoryIcon(string $href, string $hint = '', $path = ''): string
    {
        if (!$hint) {
            $hint = 'История';
        }

        $href = "{$href}?tab=history";
        $icon = getIconHtml('streamline.regular.interface-time-clock-circle', 16, Color::SECONDARY, path: $path);

        return "<span><a title='{$hint}' href='{$href}' style='cursor: pointer;'>{$icon}</a></span>";
    }
}

if (!function_exists('rowDeleteIcon')) {
    function rowDeleteIcon($id, string $caption = '', string $hint = '', bool $noJs = false, $path = ''): string
    {
        if (!$hint) {
            $hint = 'Удалить';
        }
        $icon = getIconHtml('streamline.regular.delete', 16, Color::DANGER, path: $path);

        return "<span data-row-id='{$id}' data-row-caption='{$caption}' title='{$hint}' class='js-row-delete' style='cursor: pointer'>{$icon}</span>";
    }
}

if (!function_exists('rowAddIcon')) {
    function rowAddIcon($id, string $caption = '', string $hint = '', bool $noJs = false, $path = ''): string
    {
        if (!$hint) {
            $hint = 'Добавить';
        }
        $icon = getIconHtml('streamline.regular.add', 16, Color::SUCCESS, path: $path);

        return "<span data-row-id='{$id}' data-row-caption='{$caption}' title='{$hint}' class='js-row-add' style='cursor: pointer'>{$icon}</span>";
    }
}

if (!function_exists('rowDownIcon')) {
    function rowDownIcon($id, string $caption = '', string $hint = '', bool $noJs = false, $path = ''): string
    {
        if (!$hint) {
            $hint = 'Вниз';
        }
        $icon = getIconHtml('streamline.regular.arrow-thick-down', 16, Color::PRIMARY, Color::PRIMARY, path: $path);

        return "<span data-row-id='{$id}' data-row-caption='{$caption}' title='{$hint}' class='js-row-down' style='cursor: pointer'>{$icon}</span>";
    }
}

if (!function_exists('rowUpIcon')) {
    function rowUpIcon($id, string $caption = '', string $hint = '', bool $noJs = false, $path = ''): string
    {
        if (!$hint) {
            $hint = 'Вверх';
        }
        $icon = getIconHtml('streamline.regular.arrow-thick-up', 16, Color::PRIMARY, Color::PRIMARY, path: $path);

        return "<span data-row-id='{$id}' data-row-caption='{$caption}' title='{$hint}' class='js-row-up' style='cursor: pointer'>{$icon}</span>";
    }
}

if (!function_exists('boolIcon')) {
    function boolIcon($value, $path = ''): string
    {
        if ($value) {
            $icon = getIconHtml('streamline.regular.check-double', 16, Color::PRIMARY, Color::PRIMARY, path: $path);
        } else {
            $icon = getIconHtml('streamline.regular.remove-bold', 13, Color::SECONDARY, Color::SECONDARY, path: $path);
        }

        return "<span>{$icon}</span>";
    }
}

if (!function_exists('getIconHtml')) {
    function getIconHtml(string $icon, $size = 18, $stroke = 'currentColor', $fill = 'none', $class = '', $path = ''): string
    {
        return view($path ? "{$path}.{$icon}" : "omx-icon::{$icon}", [ 'class' => $class, 'width' => $size, 'height' => $size, 'stroke' => $stroke, 'fill' => $fill ])->render();
    }
}
