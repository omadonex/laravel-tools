<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Services\OmxService;

abstract class AclNavbarService extends OmxService
{
    public const ACL_INNER_CHECK = 'aclInnerCheck';
    public const RIGHT_ICON = 'rightIcon';

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
    protected abstract function captionItemHtml(string $name, string $badge = '', array $badgeParams = [], array $optParams = []): string;
    protected abstract function singleItemTemplateHtml(string $url, string $name, string $status, string $icon = '', string $badge = '', array $badgeParams = [], array $optParams = []): string;
    protected abstract function listItemTemplateHtml(string $url, string $name, string $status, string $subHtml, string $uniqueSubIndex, string $icon = '', string $badge = '', array $badgeParams = [], array $optParams = []): string;

    private function makeOptParams(User $user, array $opt): array
    {
        $optParams = [];
        foreach ($opt as $optKey => $optData) {
            $conditionDataMethod = $optData['conditionDataMethod'];
            $condition = $optData['condition'];
            $conditionField = $condition['field'];
            $conditionPass = $this->aclService->hasAdminAccess($user) ?: $user->$conditionDataMethod()->$conditionField == $condition['value'];

            switch ($optKey) {
                case self::ACL_INNER_CHECK:
                    $optParams[$optKey] = $conditionPass;
                    break;
                default:
                    if ($conditionPass) {
                        $optParams[$optKey] = $optData['params'];
                    }
            }
        }

        return $optParams;
    }

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

            $user = $this->aclService->user();
            $optParams = $this->makeOptParams($user, $menuItem['opt'] ?? []);
            if (array_key_exists(self::ACL_INNER_CHECK, $optParams)) {
                $access = $optParams[self::ACL_INNER_CHECK];
            }

            if ($access) {
                if ($menuItem['line'] ?? false) {
                    $html .= $this->lineItemHtml();
                    continue;
                }

                if ($menuItem['caption'] ?? false) {
                    $badge = $menuItem['badge'] ?? '';
                    $badgeParams = $menuItem['badgeParams'] ?? [];
                    $html .= $this->captionItemHtml($menuItem['name'], $badge, $badgeParams, $optParams);
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

                $replacedMenuRouteName = preg_replace('/.index$/', '', $menuItem['route']);
                if (strpos($currentRouteName, $replacedMenuRouteName) !== false) {
                    $arrCurrent = explode('.', $currentRouteName);
                    array_pop($arrCurrent);
                    $arrReplacedMenu = explode('.', $replacedMenuRouteName);
                    $same = true;
                    $i = 0;
                    if (count($arrCurrent) > count($arrReplacedMenu)) {
                        $same = false;
                    } else {
                        while ($i < count($arrReplacedMenu)) {
                            if ($arrCurrent[$i] != $arrReplacedMenu[$i]) {
                                $same = false;
                                break;
                            }
                            $i++;
                        }
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
                            $html .= $this->listItemTemplateHtml($route, $name, $status, $subHtml, "{$level}_{$index}", $icon, $badge, $badgeParams, $optParams);
                        } else {
                            $html .= $this->singleItemTemplateHtml($route, $name, $status, $icon, $badge, $badgeParams, $optParams);
                        }
                    }
                } else {
                    $html .= $this->singleItemTemplateHtml($route, $name, $status, $icon, $badge, $badgeParams, $optParams);
                }
            }

            $index++;
        }

        return $html;
    }
}
