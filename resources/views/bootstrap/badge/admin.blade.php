@php
    use Omadonex\LaravelTools\Support\Tools\Color;

    $badgeParams = isset($badgeParams) ? $badgeParams : [];
    $roundedPill = ($badgeParams['roundedPill'] ?? false) ? 'rounded-pill' : '';
    $msAuto = ($badgeParams['msAuto'] ?? false) ? 'ms-auto' : '';
    $classList = $badgeParams['classList'] ?? '';
@endphp

{!! getIconHtml('streamline.bold.lock-1', 18, Color::CURRENT, Color::WARNING, 'nav-link-icon') !!}
<span class="badge text-bg-warning {{ $roundedPill }} {{ $msAuto }} {{ $classList }}">{{ __('omx-support::pageDefault.badge.admin') }}</span>
