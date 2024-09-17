<?php

namespace Omadonex\LaravelTools\Support\Bootstrap\Constructor;

use Omadonex\LaravelTools\Acl\Services\AclNavbarService as OmxAclNavbarService;

class Navbar extends OmxAclNavbarService
{
    protected function lineItemHtml(): string
    {
        return '<li class="nav-item w-100"><hr></li>';
    }

    protected function captionItemHtml(string $name, string $badge = '', array $badgeParams = []): string
    {
        $badgeHtml = $this->badgeHtml($badge, $badgeParams);

        return "
            <li class=\"nav-item\">
                <a class=\"nav-link disabled\">
                    {$badgeHtml}
                    <span class='mx-3'>{$name}</span>
                </a>
            </li>
        ";
    }

    private function iconHtml(string $icon): string
    {
        return empty($icon) ? '' : getIconHtml($icon, 18, 'currentColor', 'none', 'nav-link-icon');
    }

    private function badgeHtml(string $badge, array $badgeParams): string
    {
        return !$badge ? '' : view("omx-bootstrap::badge.{$badge}", ['badgeParams' => $badgeParams])->render();
    }

    protected function singleItemTemplateHtml(
        string $url,
        string $name,
        string $status,
        string $icon = '',
        string $badge = '',
        array $badgeParams = []
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
        array $badgeParams = []
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
