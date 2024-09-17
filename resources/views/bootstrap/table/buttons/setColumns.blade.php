@if (isset($readonly) && $readonly)
    <input id="{{ $tableId }}_columns_data" type="hidden" value="{{ json_encode($columnsData) }}"/>
@else
<div class="d-flex" style="justify-content: end;  margin-left: 15px;">
    <input id="{{ $tableId }}_columns_data" type="hidden" value="{{ json_encode($columnsData) }}"/>

    <form method="POST" id="{{$tableId}}__form" action="{{ route('setTableColumns') }}">
        @csrf
        <input type="hidden" name="pageId"  value="{{ $pageId }}" />
        <input type="hidden" name="tableId" value="{{ $tableId }}" />
        <input type="hidden" name="backTab" value="{{ $backTab }}">

        <div style="width: 200px; margin-right: 5px">
            @include('omx-form::bs52.partials.select', [
               'cmpId'           => "{$tableId}__colSelect",
               'cmpName'         => "{$tableId}__filter_columns",
               'cmpList'         => $tableColumnSetList,
               'cmpNoLabel'      => true,
               'cmpClass'        => 'form-control-sm columns-selector',
               'cmpValue'        => json_encode($columns),
               'cmpPlaceholder'  => '',
               'cmpHideSelected' => false
           ])
        </div>
    </form>

    <div>
        @include('omx-bootstrap::table.modal.columnSetCreate')

        <button id="{{ $tableId }}__btnCreateColumns" type="button" class="btn btn-success btn-sm" title="Создать набор колонок" style="padding: 6px 9px;">
            {!! getIconHtml('streamline.bold.add-bold', 11, 'currentColor', 'currentColor') !!}
        </button>

{{--        <a class="btn btn-secondary btn-sm" title="Редактировать набор колонок" style="padding: 6px 8px;" href="{{ route('admin.setting.column-set.index', [--}}
{{--               'tableId'   => $tableId,--}}
{{--               'viewClass' => get_class($view),--}}
{{--               'backUrl'   => $backUrl ?? url()->current()--}}
{{--            ]) }}">--}}
{{--            {!! getIconHtml('streamline.regular.edit', 12, 'currentColor', 'currentColor') !!}--}}
{{--        </a>--}}
    </div>
</div>
@endif
