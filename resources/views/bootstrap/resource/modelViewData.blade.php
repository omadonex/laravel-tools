@php
    /** @var \Omadonex\LaravelTools\Support\ModelView\ModelView $view */
    $columns = $view->getColumns(isset($columnList) ? $columnList : []);
    $columnsCount = count($columns);
    $singleColumn = isset($singleColumn) ? $singleColumn : false;
    $viewColumnsCount = $singleColumn ? 1 : $view->getViewColunnsCount();
    $countPerColumn = intdiv($columnsCount, $viewColumnsCount);

    $columnKeys = array_keys($columns);
@endphp

@for($k = 0; $k < $viewColumnsCount; $k++)
    @php
        $start = $k * $countPerColumn;
        $end = ($k == $viewColumnsCount - 1) ? $columnsCount : ($k + 1) * $countPerColumn;
    @endphp
    @if (!$singleColumn) <div class="col-lg-4 py-5"> @endif
        <table class="table table-striped table-condensed">
            <tbody>
            @for($i = $start; $i < $end; $i ++)
                @php
                    $column = $columnKeys[$i];
                    $columnData = $columns[$column];
                    $type = $view->getType($column);
                @endphp
                @if ($type !== 'none')
                    <tr>
                        <td>
                            {{ $view->getLabel($column) }}
                            @switch($type)
                                @case('percent')
                                    , %
                                    @break
                                @case('money')
                                    @php
                                        $moneyData = $view->getMoneyData($column);
                                        if ($moneyData['currencyField'] ?? false) {
                                            $currencyField = $moneyData['currencyField'];
                                            $currency = $model->$currencyField;
                                        } else {
                                            $currency = $moneyData['currency'];
                                        }
                                    @endphp
                                    @if (!($moneyData['noSign'] ?? false))
                                        {!! \Omadonex\LaravelTools\Support\Tools\Caption::currencySign($currency) !!}
                                    @endif
                                    @break
                                @case('callback')
                                    @php
                                        $callbackData = $view->getCallbackData($column);
                                        $callbackName = $callbackData['name'];
                                        $callbackType = $callbackData['type'];
                                        $callbackInfo = $callbackData['info'] ?? [];
                                    @endphp
                                    @switch($callbackType)
                                        @case('percent')
                                            , %
                                            @break
                                        @case('money')
                                            @php
                                                $moneyData = $callbackInfo;
                                                if ($moneyData['currencyField'] ?? false) {
                                                    $currencyField = $moneyData['currencyField'];
                                                    $currency = $model->$currencyField;
                                                } else {
                                                    $currency = $moneyData['currency'];
                                                }
                                            @endphp
                                            @if (!($moneyData['noSign'] ?? false))
                                                {!! \Omadonex\LaravelTools\Support\Tools\Caption::currencySign($currency) !!}
                                            @endif
                                            @break
                                    @endswitch
                                    @break
                            @endswitch
                        </td>
                        <td style="text-align: right">
                            @switch($type)
                                @case('bool')
                                    {!! boolIcon($model->$column) !!}
                                    @break
                                @case('dt')
                                    @if ($model->$column)
                                        {{ $model->$column->timezone('Europe/Moscow')->format($view->getDateData($column)['format'])  }}
                                    @else
                                        {!! Omadonex\LaravelTools\Support\Tools\Caption::EMPTY !!}
                                    @endif
                                    @break
                                @case('money')
                                    {{ number_format($model->$column, 2, ',', ' ') }}
                                    @break
                                @case('relation')
                                    @php
                                        $relationData = $view->getRelationData($column);
                                        $relationName = $relationData['name'];
                                        $relationField = $relationData['field'];
                                        $relationType = $relationData['type'];
                                    @endphp
                                    @if ($relationType === 'bool')
                                        {!! boolIcon($model->$relationName->$relationField ?? false) !!}
                                    @else
                                        {!! $model->$relationName->$relationField ?? \Omadonex\LaravelTools\Support\Tools\Caption::EMPTY !!}
                                    @endif
                                    @break
                                @case('translate')
                                    @php
                                        $translateData = $view->getTranslateData($column);
                                        $translateField = $translateData['field'];
                                    @endphp
                                    {{ $model->getTranslate()->$translateField }}
                                    @break
                                @case('key')
                                    @php
                                        $keyData = $view->getKeyData($column);
                                        $keyField = $keyData['field'];
                                        $keyType = $keyData['type'];
                                    @endphp
                                    @switch($keyType)
                                        @case('currency')
                                            {{ \Omadonex\LaravelTools\Support\Tools\Caption::currency($model->$keyField) }}
                                            @break
                                        @default
                                            {{ $model->$keyField }}
                                    @endswitch
                                    @break
                                @case('callback')
                                    @php
                                        $callbackData = $view->getCallbackData($column);
                                        $callbackName = $callbackData['name'];
                                        $callbackType = $callbackData['type'];
                                        $callbackInfo = $callbackData['info'] ?? [];
                                    @endphp
                                    @switch($callbackType)
                                        @case('money')
                                            {{ number_format($view->$callbackName($model), 2, ',', ' ') }}
                                            @break
                                        @default
                                            {{ $view->$callbackName($model) }}
                                    @endswitch
                                    @break
                                @default
                                    {{ $model->$column }}
                            @endswitch
                        </td>
                    </tr>
                @endif
            @endfor
            </tbody>
        </table>
    @if (!$singleColumn) </div> @endif
@endfor
