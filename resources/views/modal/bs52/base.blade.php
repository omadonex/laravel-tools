@php
    $btnSize = isset($btnSize) ? $btnSize : '';
    $spinnerSize = '';
    if (isset($spinner) && $btnSize !== '') {
        $spinnerSize = "{$spinner}-{$btnSize}";
    }
    $btnSize = "btn-{$btnSize}";
@endphp

<div id="{{ $modalId }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}__title" aria-hidden="true">
    <div class="modal-dialog {{ $modalClass ?? '' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="{{ $modalId }}__title">{{ $title }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div>
                <div class="modal-body" style="position: relative;">
                    <div id="{{ $modalId }}__overlay" class="omx-overlay d-none">
                        @yield("{$modalId}-overlay")
                        <div class="omx-overlay-text">Подождите.<br/>Идет загрузка...</div>
                    </div>
                    <div class="message-list-box"></div>
                    @yield("{$modalId}-body")
                </div>
                @if (!isset($hideFooter) || !$hideFooter)
                    <div class="modal-footer">
                        <button id="{{ $modalId }}__btn_cancel" type="button" class="btn btn-light {{ $btnSize }}" data-bs-dismiss="modal">Отмена</button>
                        @yield("{$modalId}-footer")
                        <button id="{{ $modalId }}__btn_submit" type="button" class="btn btn-primary {{ $btnSize }}">
                            @if (isset($spinner))
                                <span id="{{ $modalId }}__btn_submit_spinner" style="{{ $spinnerStyle }}" class="{{ $spinner }} {{ $spinnerSize }} float-end d-none" role="status" aria-hidden="true"></span>
                            @endif
                            Ок
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
