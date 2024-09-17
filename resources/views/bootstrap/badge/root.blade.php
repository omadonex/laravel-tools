@php
    use Omadonex\LaravelTools\Support\Tools\Color;
    $badgeParams = isset($badgeParams) ? $badgeParams : [];
    $roundedPill = ($badgeParams['roundedPill'] ?? false) ? 'rounded-pill' : '';
    $msAuto = ($badgeParams['msAuto'] ?? false) ? 'ms-auto' : '';
    $classList = $badgeParams['classList'] ?? '';
@endphp

{!! getIconHtml('streamline.bold.army-shield', 18, Color::CURRENT, Color::DANGER, 'nav-link-icon') !!}
<span class="badge text-bg-danger {{ $roundedPill }} {{ $msAuto }} {{ $classList }}">{{ __('omx-support::pageDefault.badge.root') }}</span>
