@php
    $badgeParams = isset($badgeParams) ? $badgeParams : [];
    $roundedPill = ($badgeParams['roundedPill'] ?? false) ? 'rounded-pill' : '';
    $msAuto = ($badgeParams['msAuto'] ?? false) ? 'ms-auto' : '';
    $classList = $badgeParams['classList'] ?? '';
@endphp

<span class="badge text-bg-success {{ $roundedPill }} {{ $msAuto }} {{ $classList }}">{{ __('omx-support::pageDefault.badge.new') }}</span>
