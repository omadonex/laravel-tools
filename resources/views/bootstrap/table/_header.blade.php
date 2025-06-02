@php
    /** @var array $page */
    /** @var array $table  */

    $tableTitle = $table['ext']['title'];
    $tableColumnSetList = $table['columnSetList'];

    $pageId = $page['id'];
    $pageIdBack = $page['idBack'];

    $modalCaptions = $table['ext']['captions'];
    $modalWidth = $table['ext']['modalWidth'] ?? false;

    $backTab = isset($backTab) ? $backTab : null;
@endphp

@if (!isset($noHeader) || !$noHeader)
<h2 class="card-header-title h4 text-uppercase">
    @if (!isset($specHeader) || !$specHeader)
        Таблица <i>"{{ $tableTitle }}"</i> @if (in_array('history', $tableModeList))<a href="{{ route("{$tablePath}.history") }}">(История изменений)</a>@endif
    @endif
    @yield('table-header')
</h2>
@endif
@if (in_array('create', $tableModeList))
    @include("omx-bootstrap::table.modal.create")
@endif
@if (in_array('edit', $tableModeList))
    @include("omx-bootstrap::table.modal.edit")
@endif
@if (in_array('destroy', $tableModeList))
    @include('omx-bootstrap::modal.confirmDelete')
@endif

@yield('table-buttons')

@if (in_array('export', $tableModeList))
    @include('omx-bootstrap::resource.export._form', ['formId' => "{$tableId}__formExport", 'method' => 'POST', 'action' => route("{$tablePath}.export"), 'tableParams' => isset($tableParams) ? $tableParams : []])
    @include('omx-bootstrap::buttons.standard.export', ['btnEntityId' => $tableId, 'btnStyle' => 'margin-right: 1em;'])
@endif
@if (in_array('import', $tableModeList))
    @include('omx-bootstrap::buttons.standard.import', ['btnEntityId' => $tableId, 'btnStyle' => 'margin-right: 1em;', 'btnHref' => route("{$tablePath}.import")])
@endif
@if (in_array('create', $tableModeList))
    @include('omx-bootstrap::buttons.standard.create', ['btnEntityId' => $tableId, 'btnStyle' => 'margin-right: 1em;'])
@endif
@if (in_array('filter', $tableModeList))
    @include('omx-bootstrap::table.buttons.clearFilter')
@endif
@include('omx-bootstrap::table.buttons.setColumns', ['readonly' => !in_array('column', $tableModeList)])
