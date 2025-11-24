<?php

namespace Omadonex\LaravelTools\Support\Constructor\Template;

use Omadonex\LaravelTools\Acl\Services\AclNavbarService as OmxAclNavbarService;

class NavbarService extends OmxAclNavbarService
{
    protected function lineItemHtml(): string
    {
        return '<li class="nav-item w-100"><hr></li>';
    }

    protected function captionItemHtml(string $name, string $badge = '', array $badgeParams = [], array $optParams = []): string
    {
        $badgeHtml = $this->badgeHtml($badge, $badgeParams);

        $rightIconHtml = '';
        if ($optParams) {
            if ($optParams['rightIcon'] ?? false) {
                $rightIcon = $optParams['rightIcon'];
                $rightIconHtml = $this->iconHtml($rightIcon['icon'], $rightIcon['fill'], $rightIcon['stroke']);
            }
        }

        return "
            <li class=\"nav-item\">
                <a class=\"nav-link disabled\">
                    {$badgeHtml}
                    <span class='mx-3'>{$name}</span>
                    {$rightIconHtml}
                </a>
            </li>
        ";
    }

    private function iconHtml(string $icon, string $color = 'currentColor', string $stroke = 'none'): string
    {
        return empty($icon) ? '' : getIconHtml($icon, 18, $color, $stroke, 'nav-link-icon');
    }

    private function badgeHtml(string $badge, array $badgeParams): string
    {
        $path = in_array($badge, ['root', 'admin', 'user', 'new']) ? "omx-bootstrap::badge.{$badge}" : "partials.badge.navbar.{$badge}";

        return !$badge ? '' : view($path, ['badgeParams' => $badgeParams])->render();
    }

    protected function singleItemTemplateHtml(
        string $url,
        string $name,
        string $status,
        string $icon = '',
        string $badge = '',
        array $badgeParams = [],
        array $optParams = []
    ): string {
        $iconHtml = $this->iconHtml($icon);
        $badgeHtml = $this->badgeHtml($badge, $badgeParams);

        return "
            <li class=\"nav-item\">
                <a class=\"nav-link {$status}\" href=\"{$url}\" title=\"{$name}\">
                    {$iconHtml}
                    <span>{$name}</span>
                    {$badgeHtml}
                </a>
            </li>
        ";
    }

    protected function rootItemAttributes(): string
    {
        return 'class="navbar-nav mb-lg-7"';
    }

    protected function listItemTemplateHtml(
        string $url,
        string $name,
        string $status,
        string $subHtml,
        string $uniqueSubIndex,
        string $icon = '',
        string $badge = '',
        array $badgeParams = [],
        array $optParams = []
    ): string {
        $iconHtml = $this->iconHtml($icon);
        $badgeHtml = $this->badgeHtml($badge, $badgeParams);
        $id = "menu_{$uniqueSubIndex}_collapse";
        $subStatus = $status ? 'show' : '';

        return "
            <li class=\"nav-item dropdown\">
                <a class=\"nav-link {$status}\" href=\"#{$id}\" data-bs-toggle=\"collapse\" role=\"button\" aria-controls=\"{$id}\" title=\"{$name}\">
                    {$iconHtml}
                    <span>{$name}</span>
                    {$badgeHtml}
                </a>
                <div class=\"collapse {$subStatus}\" id=\"{$id}\">
                    <ul class=\"nav flex-column\">
                        {$subHtml}
                    </ul>
                </div>
            </li>
        ";
    }
}
