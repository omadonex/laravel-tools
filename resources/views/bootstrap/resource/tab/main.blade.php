<div class="tab-pane fade @if ($pageTab == 'main') show active @endif" id="{{ $pageId }}__tabCard__paneMain" role="tabpanel" aria-labelledby="{{ $pageId }}__tabCard__buttonMain" tabindex="0">
    <div class="row">
        @include('omx-bootstrap::resource.modelViewData')
        @yield('show-tab-main-extContent')
    </div>
</div>
