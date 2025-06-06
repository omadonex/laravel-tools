<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Support\Services\OmxService;

abstract class AclNavbarService extends OmxService
{
    protected AclService $aclService;

    public function __construct(IAclService $aclService)
    {
        $this->aclService = $aclService;
    }

    public function generate(array $data)
    {
        $html = $this->rootItemAttributes();

        $html = "<ul {$html}>";
        $html .= self::walkMenuData($data);
        $html .= '</ul>';

        return $html;
    }

    protected abstract function rootItemAttributes(): string;
    protected abstract function lineItemHtml(): string;
    protected abstract function captionItemHtml(string $name, string $badge = '', array $badgeParams = []): string;
    protected abstract function singleItemTemplateHtml(string $url, string $name, string $status, string $icon = '', string $badge = '', array $badgeParams = []): string;
    protected abstract function listItemTemplateHtml(string $url, string $name, string $status, string $subHtml, string $uniqueSubIndex, string $icon = '', string $badge = '', array $badgeParams = []): string;

    private function walkMenuData($data, $level = 0)
    {
        $currentRouteName = Route::currentRouteName();
        $html = '';
        $index = 0;
        foreach ($data as $menuItem) {
            $access = true;
            if ($permission = $menuItem['permission'] ?? null) {
                $access = $permission ? $this->aclService->check($permission) : true;
            }
            if ($role = $menuItem['role'] ?? null) {
                $access = $access && ($role ? $this->aclService->checkRole($role) : true);
            }

            if ($access) {
                if ($menuItem['line'] ?? false) {
                    $html .= $this->lineItemHtml();
                    continue;
                }
                if ($menuItem['caption'] ?? false) {
                    $badge = $menuItem['badge'] ?? '';
                    $badgeParams = $menuItem['badgeParams'] ?? [];
                    $html .= $this->captionItemHtml($menuItem['name'], $badge, $badgeParams);
                    continue;
                }

                $name = $menuItem['t'] ?? false ? __($menuItem['name']) : $menuItem['name'];
                $sub = $menuItem['sub'] ?? [];
                $icon = $menuItem['icon'] ?? '';
                $badge = $menuItem['badge'] ?? '';
                $status = $menuItem['status'] ?? '';
                $badgeParams = $menuItem['badgeParams'] ?? [];
                if ($menuItem['route'] === '#') {
                    $route = '#';
                } else {
                    $route = ($menuItem['static'] ?? false) ? $menuItem['route'] : route($menuItem['route']);
                    //TODO omadonex: params for routes
                }

                $replacedRouteName = preg_replace('/.index$/', '', $menuItem['route']);
                if (strpos($currentRouteName, $replacedRouteName) !== false) {
                    $arrCurrent = explode('.', $currentRouteName);
                    $arrReplaced = explode('.', $replacedRouteName);
                    $same = true;
                    $i = 0;
                    while ($i < count($arrReplaced)) {
                        if ($arrCurrent[$i] != $arrReplaced[$i]) {
                            $same = false;
                            break;
                        }
                        $i++;
                    }

                    if ($same) {
                        $status = 'active';
                    }
                }

                if ($sub) {
                    $subHtml = self::walkMenuData($menuItem['sub'], $level + 1);
                    if (strpos($subHtml, 'active')) {
                        $status = 'active';
                    }
                    if ($subHtml || ($menuItem['route'] !== '#')) {
                        if ($subHtml) {
                            $html .= $this->listItemTemplateHtml($route, $name, $status, $subHtml, "{$level}_{$index}", $icon, $badge, $badgeParams);
                        } else {
                            $html .= $this->singleItemTemplateHtml($route, $name, $status, $icon, $badge, $badgeParams);
                        }
                    }
                } else {
                    $html .= $this->singleItemTemplateHtml($route, $name, $status, $icon, $badge, $badgeParams);
                }
            }

            $index++;
        }

        return $html;
    }
}
