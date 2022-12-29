<div id="modal-{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-{{$id}}__title" aria-hidden="true">
    <div class="modal-dialog {{ $modalClass ?? '' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-{{$id}}__title">{{ $title }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div style="position: relative;">
                <div id="modal-{{ $id }}__overlay" class="omx-overlay">
                    <div class="omx-overlay-text">Подождите.<br/>Идет загрузка...</div>
                </div>
                <div class="modal-body">
                    <div class="message-list-box"></div>
                    @yield("body-{$id}")
                </div>
                @if (!isset($hideFooter) || !$hideFooter)
                    <div class="modal-footer">
                        <button id="modal-{{ $id }}__btn_cancel" type="button" class="btn btn-light js-btn-no {{ $btnSize }}" data-bs-dismiss="modal">Отмена</button>
                        @yield("footer-{$id}")
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
