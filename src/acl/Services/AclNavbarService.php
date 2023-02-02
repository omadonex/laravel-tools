<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

abstract class AclNavbarService
{
    protected AclService $aclService;
    protected array $data;

    public function __construct(IAclService $aclService)
    {
        $this->aclService = $aclService;
        $this->data = config('omx.acl.navbar', []);
    }

    public function generate()
    {
        $html = $this->rootItemAttributes();

        $html = "<ul {$html}>";
        $html .= self::walkMenuData($this->data);
        $html .= '</ul>';

        return $html;
    }

    protected abstract function rootItemAttributes(): string;
    protected abstract function lineItemHtml(): string;
    protected abstract function captionItemHtml(string $name, string $badge = '', array $badgeParams = []): string;
    protected abstract function singleItemTemplateHtml(string $url, string $name, string $icon = '', string $badge = '', array $badgeParams = []): string;
    protected abstract function listItemTemplateHtml(string $url, string $name, string $subHtml, string $uniqueSubIndex, string $icon = '', string $badge = '', array $badgeParams = []): string;

    private function walkMenuData($data, $level = 0)
    {
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
                $badgeParams = $menuItem['badgeParams'] ?? [];
                if ($menuItem['route'] === '#') {
                    $route = '#';
                } else {
                    $route = ($menuItem['static'] ?? false) ? $menuItem['route'] : route($menuItem['route']);
                    //TODO omadonex: params for routes
                }

                if ($sub) {
                    $subHtml = self::walkMenuData($menuItem['sub'], $level + 1);
                    if ($subHtml || ($menuItem['route'] !== '#')) {
                        if ($subHtml) {
                            $html .= $this->listItemTemplateHtml($route, $name, $subHtml, "{$level}_{$index}", $icon, $badge, $badgeParams);
                        } else {
                            $html .= $this->singleItemTemplateHtml($route, $name, $icon, $badge, $badgeParams);
                        }
                    }
                } else {
                    $html .= $this->singleItemTemplateHtml($route, $name, $icon, $badge, $badgeParams);
                }
            }

            $index++;
        }

        return $html;
    }
}
