<div class="table-responsive pb-5" style="overflow: hidden;">
    <input id="{{ $tableId }}_urlData" type="hidden" value="{{ route("{$tablePath}.data", isset($modelId) ? ["{$tableId}__filter_model_id" => $modelId] : null) }}"/>
    <table id="{{ $tableId }}" class="table table-nowrap mb-0 w-100">
        <thead class="thead-light">
        <tr>
            <th>ID истории</th>
            <th>Пользователь</th>
            <th>ID записи</th>
            <th>Событие</th>
            <th>Дата</th>
            <th>Данные записи</th>
        </tr>
        </thead>
        <tbody class="list"></tbody>
    </table>
</div>
