<div class="table-responsive" style="margin-bottom: 15px; overflow: hidden;">
    <input id="{{ $tableId }}_urlData" type="hidden" value="{{ route($tablePathList['data'], isset($tableParams) ? $tableParams : null) }}"/>
    @if (in_array('edit', $tableModeList))
        <input id="{{ $tableId }}_urlRowData" type="hidden" value="{{ route($tablePathList['edit'], '*') }}"/>
    @endif
    @if (in_array('destroy', $tableModeList))
        <input id="{{ $tableId }}_urlRowDelete" type="hidden" value="{{ route($tablePathList['destroy'], '*') }}"/>
    @endif

    @yield('table-hidden')

    <table id="{{ $tableId }}" class="table table-nowrap mb-0">
        <thead class="thead-light">
        @if (in_array('filter', $tableModeList))
            <tr class="filter">
                @include('omx-bootstrap::table.columnFilters')
            </tr>
        @endif
        <tr>
            @include('omx-bootstrap::table.columnHeaders')
        </tr>
        </thead>
        <tbody class="list"></tbody>
        @if (isset($tableFooter) && $tableFooter)
            <tfoot>
            <tr>
                @if ($view->hasPrependEmpty())
                    <th></th>
                @endif
                @if ($view->hasActionsPre())
                    <th></th>
                @endif
                @for($i = 0; $i < count($columns); $i++)
                    <th></th>
                @endfor
                @foreach($view->getSpecificColumns() as $columnSpecificData)
                    <th></th>
                @endforeach
                @if ($view->hasActions())
                    <th></th>
                @endif
            </tr>
            </tfoot>
        @endif
    </table>
</div>
