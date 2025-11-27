@php
    /** @var array $page */
    /** @var array $table  */
    /** @var \Omadonex\LaravelTools\Support\ModelView\ModelView $view */

    $tableId = $table['id'];
    $tablePath = $table['ext']['path'];
    $tableFormCreate = $table['ext']['formCreate'];
    $tableFormEdit = $table['ext']['formEdit'];
    $tableModeList = $table['modeList'];
    $tableAclDeniedModeList = $table['aclDeniedModeList'];

    $view = $table['ext']['view'];
    $filter = $page['filter'];

    $tableColumns = isset($tableColumns) ? $tableColumns : [];
    $columnsList = $tableColumns['list'] ?? [];
    $notIncluded = $tableColumns['notIncluded'] ?? false;
    $showHidden = $tableColumns['showHidden'] ?? false;

    list($columnsData, $columns) = $view->columnsInfo($filter, $tableId, $columnsList, $notIncluded, $showHidden);
@endphp

@if (isset($tableInTab) ? $tableInTab : false)
    @include('omx-bootstrap::table.templateInTab')
@else
    @include('omx-bootstrap::table.templateDefault')
@endif
