@php
    /** @var string $btnActionId */
    $toolsCLass = isset($btnActionToolsClass) ? $btnActionToolsClass : \Omadonex\LaravelTools\Support\Tools\ButtonAction::class;
    $btnData = $toolsCLass::data($btnActionId);
    $btnIcon = $btnIcon ?? $btnData['icon'] ?? null;
@endphp

@include('omx-form::bs52.partials.button', [
    'btnEntityId' => $btnEntityId,
    'btnText' => $btnText ?? $btnData['text'] ?? '',
    'btnContext' => $btnContext ?? $btnData['context'] ?? \Omadonex\LaravelTools\Support\Tools\Context::DEFAULT,
    'btnSize' => $btnSize ?? \Omadonex\LaravelTools\Support\Tools\Size::SM,
    'btnIconHtml' => $btnIcon ? getIconHtml($btnIcon, $btnIconSize ?? 14, $btnIconStroke ?? 'currentColor', $btnIconFill ?? 'currentColor') : null,
    'btnIconPos' => $btnIconPos ?? $btnData['iconPos'] ?? 'left',
])
