<form method="POST" action="{{ route('filterClear') }}">
    @csrf
    <input type="hidden" value="{{ $pageIdBack }}" name="pageId"/>
    <input type="hidden" value="{{ $tableId }}" name="tableId"/>

    @isset($pageTab)
        <input type="hidden" value="{{ $pageTab }}" name="tab"/>
    @endisset

    <button id="{{ $pageId }}__btnClearFilter" data-page-id="{{ $pageIdBack }}" type="submit" class="btn btn-secondary btn-sm" @isset($style) style="{{ $style }}" @endisset>
        <span>Сбросить фильтр</span>
    </button>
</form>
