<div class="card-header border-0 card-header-space-between" @if (isset($noHeader) && $noHeader) style="padding: 0;" @endif>
    @include('omx-bootstrap::table._header')
</div>
@include('omx-bootstrap::table._body')
