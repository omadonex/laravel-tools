<div class="tab-pane fade @if ($pageTab == 'history') show active @endif" id="{{ $pageId }}__tabCard__paneHistory" role="tabpanel" aria-labelledby="{{ $pageId }}__tabCard__buttonHistory" tabindex="0">
    <div class="col-lg-12 pt-5">
        @include('omx-bootstrap::table.history', ['tableId' => "{$tableId}ModelHistory", 'tablePath' => "{$tablePath}.history", 'modelId' => $model->getKey()])
    </div>
</div>
