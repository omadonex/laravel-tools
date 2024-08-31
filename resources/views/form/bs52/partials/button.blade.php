@php
    use Omadonex\LaravelTools\Support\Tools\Context;
    use Omadonex\LaravelTools\Support\Tools\Size;

    /** @var string $btnEntityId */
    /** @var string $btnActionId */
    /** @var string $btnHref */
    /** @var string $btnSubmit */
    /** @var string $btnContext */
    /** @var string $btnSize */
    /** @var string $btnClass */
    /** @var string $btnStyle */
    /** @var string $btnIconHtml */
    /** @var string $btnText */
    /** @var string $btnSpinner */
    /** @var bool   $btmFlat  */
    /** @var array  $btnAttrs */

    $btnSubmit = isset($btnSubmit) && $btnSubmit;

    if ($btnSubmit) {
        $btnActionId = 'submit';
    }
    $btnActionId = ucfirst($btnActionId);
    $id = "{$btnEntityId}__btn{$btnActionId}";

    $btnContext = isset($btnContext) ? $btnContext : Context::DEFAULT;
    $contextClass = $btnContext ? "btn-{$btnContext}" : '';

    $btnSpinner = isset($btnSpinner) ? $btnSpinner : 'spinner-grow';

    $btnSize = isset($btnSize) ? $btnSize : Size::DEFAULT;
    $sizeClass = $btnSize ? "btn-{$btnSize}" : '';
    $sizeSpinnerClass = $btnSize ? "{$btnSpinner}-{$btnSize}" : '';

    $btnClass = isset($btnClass) ? $btnClass : '';
    $customClass = implode(' ', ['btn', $contextClass, $sizeClass, $btnClass]);

    $btnAttrs = isset($btnAttrs) ? $btnAttrs : [];
    $attrsStrArray = [];
    foreach ($btnAttrs as $key => $value) {
        $attrsStrArray[] = "{$key}=\"{$value}\"";
    }
    $attrsStr = implode(' ', $attrsStrArray);

    $btnFlat = $btnFlat ?? false;
@endphp

@empty($btnHref)
    @if ($btnFlat)
        <span id="{{ $id }}"
            @if ($btnSubmit)
                data-jst-submit
            @endif
            class="{{ $customClass }}"
            @isset($btnStyle) style="{{ $btnStyle }}" @endisset
            {!! $attrsStr !!}
        >
            @if ($btnSubmit)
                <span data-jst-spinner style="margin: 0.1em -.5em 0 1em" id="{{ $id }}Spinner" class="{{ $btnSpinner }} {{ $sizeSpinnerClass }} float-end d-none" role="status" aria-hidden="true"></span>
            @endif
            @if (isset($btnIconHtml) && $btnIconHtml)
                <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
            @endisset
            <span id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
        </span>
    @else
        <button id="{{ $id }}" type="button"
            @if ($btnSubmit)
                data-jst-submit
            @endif
            class="{{ $customClass }}"
            @isset($btnStyle) style="{{ $btnStyle }}" @endisset
            {!! $attrsStr !!}
        >
            @if ($btnSubmit)
                <span data-jst-spinner style="margin: 0.1em -.5em 0 1em" id="{{ $id }}Spinner" class="{{ $btnSpinner }} {{ $sizeSpinnerClass }} float-end d-none" role="status" aria-hidden="true"></span>
            @endif
            @if (isset($btnIconHtml) && $btnIconHtml)
                <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
            @endisset
            <span id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
        </button>
    @endif
@else
    @if ($btnFlat)
        <a id="{{ $id }}" href="{{ $btnHref }}"
           class="{{ $customClass }}"
           @isset($btnStyle) style="{{ $btnStyle }}" @endisset
                {!! $attrsStr !!}
        >
            @if (isset($btnIconHtml) && $btnIconHtml)
                <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
            @endisset
            <span id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
        </a>
    @else
        <a id="{{ $id }}" href="{{ $btnHref }}" type="button"
            class="{{ $customClass }}"
            @isset($btnStyle) style="{{ $btnStyle }}" @endisset
            {!! $attrsStr !!}
        >
            @if (isset($btnIconHtml) && $btnIconHtml)
                <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
            @endisset
            <span id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
        </a>
    @endif
@endempty
