@php
    use Omadonex\LaravelTools\Support\Tools\Context;
    use Omadonex\LaravelTools\Support\Tools\Size;

    /** @var string $btnEntityId */
    /** @var string $btnActionId */
    /** @var string $btnHref */
    /** @var string $btnSubmit */
    /** @var bool   $btnHasSpinner  */
    /** @var string $btnContext */
    /** @var string $btnSize */
    /** @var string $btnClass */
    /** @var string $btnStyle */
    /** @var string $btnIconHtml */
    /** @var string $btnText */
    /** @var string $btnSpinner */
    /** @var bool   $btmFlat  */
    /** @var array  $btnAttrs */
    /** @var string $btnTextStyle */

    $btnSubmit = isset($btnSubmit) && $btnSubmit;
    $btnHasSpinner = $btnSubmit || ($btnHasSpinner ?? false);

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

    $btnTextStyle = isset($btnTextStyle) ? $btnTextStyle : '';

    $btnIconPos = isset($btnIconPos) ? $btnIconPos : 'left';
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
            @if ($btnHasSpinner)
                <span data-jst-spinner style="margin: @if ($btnIconPos == 'left') 0.1em -.5em 0 1em @else 0.1em 1em 0 -.5em @endif" id="{{ $id }}Spinner" class="{{ $btnSpinner }} {{ $sizeSpinnerClass }} @if ($btnIconPos == 'left') float-end @else float-start @endif d-none" role="status" aria-hidden="true"></span>
            @endif

            @if ($btnIconPos == 'left')
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
            @else
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-left: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
            @endif
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
            @if ($btnHasSpinner)
                <span data-jst-spinner style="margin: @if ($btnIconPos == 'left') 0.1em -.5em 0 1em @else 0.1em 1em 0 -.5em @endif" id="{{ $id }}Spinner" class="{{ $btnSpinner }} {{ $sizeSpinnerClass }} @if ($btnIconPos == 'left') float-end @else float-start @endif d-none" role="status" aria-hidden="true"></span>
            @endif

            @if ($btnIconPos == 'left')
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
            @else
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-left: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
            @endif
        </button>
    @endif
@else
    @if ($btnFlat)
        <a id="{{ $id }}" href="{{ $btnHref }}"
           class="{{ $customClass }}"
           @isset($btnStyle) style="{{ $btnStyle }}" @endisset
                {!! $attrsStr !!}
        >
            @if ($btnIconPos == 'left')
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
            @else
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-left: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
            @endif
        </a>
    @else
        <a id="{{ $id }}" href="{{ $btnHref }}" type="button"
            class="{{ $customClass }}"
            @isset($btnStyle) style="{{ $btnStyle }}" @endisset
            {!! $attrsStr !!}
        >
            @if ($btnIconPos == 'left')
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
            @else
                <span style="{{ $btnTextStyle }}" id="{{ $id }}Text">{{ $btnText ?? '' }}</span>
                @if (isset($btnIconHtml) && $btnIconHtml)
                    <span style="margin-left: .5em; vertical-align: text-bottom;">{!! $btnIconHtml !!}</span>
                @endisset
            @endif
        </a>
    @endif
@endempty
