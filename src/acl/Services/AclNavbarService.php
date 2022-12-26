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
    protected abstract function singleItemTemplateHtml(string $url, string $name, string $icon = '', string $badge = '', array $badgeParams = []): string;
    protected abstract function listItemTemplateHtml(string $url, string $name, string $subHtml, string $icon = '', string $badge = '', array $badgeParams = []): string;

    private function walkMenuData($data, $level = 0)
    {
        $html = '';
        foreach ($data as $menuItem) {
            if ($menuItem['line'] ?? false) {
                $html .= $this->lineItemHtml();
                continue;
            }

            $permission = $menuItem['permission'] ?? [];
            $access = $permission === [] ? true : $this->aclService->check($permission);

            if ($access) {
                $name = $menuItem['t'] ?? false ? __($menuItem['name']) : $menuItem['name'];
                $sub = $menuItem['sub'] ?? [];
                $badge = $menuItem['badge'] ?? '';
                $badgeParams = $menuItem['badgeParams'] ?? [];
                if ($menuItem['route'] === '#') {
                    $route = '#';
                } else {
                    $route  = ($menuItem['static'] ?? false) ? $menuItem['route'] : route($menuItem['route']);
                    //TODO omadonex: params for routes
                }

                if ($sub) {
                    $subHtml = self::walkMenuData($menuItem['sub'], $level + 1);
                    if ($subHtml || ($menuItem['route'] !== '#')) {
                        if ($subHtml) {
                            $html .= $this->listItemTemplateHtml($route, $name, $subHtml, $icon, $badge, $badgeParams);
                        } else {
                            $html .= $this->singleItemTemplateHtml($route, $name, $icon, $badge, $badgeParams);
                        }
                    }
                } else {
                    $html .= $this->singleItemTemplateHtml($route, $name, $icon, $badge, $badgeParams);
                }
            }
        }

        return $html;
    }
}
