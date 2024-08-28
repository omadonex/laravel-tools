@php
    /** @var string $modalId */
    /** @var string $modalSubmitText */
    /** @var string $modalCancelText */
    /** @var string $modalSubmitContext */
    /** @var string $modalTitle */
    /** @var string $modalOverlayHtml */
    /** @var string $modalSubmitIconHtml */
    /** @var string $modalBtnSize */
    /** @var bool   $modalHideFooter  */

    $modalSubmitText = $modalSubmitText ?? 'Ok';
    $modalCancelText = $modalCancelText ?? 'Cancel';
    $modalSubmitContext = $modalSubmitContext ?? \Omadonex\LaravelTools\Support\Tools\Context::PRIMARY;
    $modalOverlayHtml = $modalOverlayHtml ?? 'Please wait.</br>Loading...';
    $modalHideFooter = $modalHideFooter ?? false;
    $modalBtnSize = $modalBtnSize ?? \Omadonex\LaravelTools\Support\Tools\Size::DEFAULT;
    $modalSubmitIconHtml = $modalSubmitIconHtml ?? '';
@endphp

<div id="{{ $modalId }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}__title" aria-hidden="true">
    <div class="modal-dialog {{ $modalClass ?? '' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="{{ $modalId }}__title">{{ $modalTitle ?? '' }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div>
                <div class="modal-body" style="position: relative;">
                    <div id="{{ $modalId }}__alert" class="alert d-none"></div>
                    <div id="{{ $modalId }}__overlay" class="omx-overlay d-none">
                        @yield("{$modalId}-overlay")
                        <div class="omx-overlay-text">{!! $modalOverlayHtml !!}</div>
                    </div>
                    <div id="{{ $modalId }}__bodyCaption">
                        @yield("{$modalId}-bodyCaption")
                    </div>
                    <div id="{{ $modalId }}__body">
                        @yield("{$modalId}-body")
                    </div>
                </div>
                @if (!$modalHideFooter)
                    <div class="modal-footer">
                        @include('omx-form::bs52.partials.button', [
                            'btnEntityId' => $modalId,
                            'btnActionId' => 'cancel',
                            'btnText' => $modalCancelText,
                            'btnAttrs' => ['data-bs-dismiss' => 'modal'],
                            'btnSize' => $modalBtnSize,
                            'btnContext' => \Omadonex\LaravelTools\Support\Tools\Context::SECONDARY,
                        ])
                        @yield("{$modalId}-footer")
                        @include('omx-form::bs52.partials.button', [
                            'btnSubmit' => true,
                            'btnEntityId' => $modalId,
                            'btnText' => $modalSubmitText,
                            'btnSize' => $modalBtnSize,
                            'btnContext' => $modalSubmitContext,
                            'btnIconHtml' => $modalSubmitIconHtml,
                        ])
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
