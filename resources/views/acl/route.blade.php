@php
    use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
@endphp

@extends('omx-common::layout')

@section('style')
    <style>
        table {
            table-layout: fixed;
            width: 100%;
            font-size: 0.7em !important;
        }

        table td {
            word-break: break-all;
        }
    </style>
@endsection

@section('body')
    <div class="pure-g">
        <div class="pure-u-1-2">
            <div style="margin: 0 .5rem 0 1rem;">
                <h4>Закрытые маршруты ({{ count($routesData[IAclService::SECTION_DENIED]) }})</h4>
                <table class="pure-table pure-table-horizontal pure-table-striped">
                    <thead>
                    <tr>
                        <th style="width: 80px;">Методы</th>
                        <th>Путь</th>
                        <th>Имя</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routesData[IAclService::SECTION_DENIED] as $routeData)
                        <tr>
                            <td>{{ implode(', ', $routeData['methods']) }}</td>
                            <td>{{ $routeData['path'] }}</td>
                            <td>{{ $routeData['name'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pure-u-1-2">
            <div style="margin: 0 1rem 0 .5rem;">
                <h4>Открытые маршруты ({{ count($routesData[IAclService::SECTION_ALLOWED]) }})</h4>
                <table class="pure-table pure-table-horizontal pure-table-striped">
                    <thead>
                    <tr>
                        <th style="width: 80px;">Методы</th>
                        <th>Путь</th>
                        <th>Имя</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routesData[IAclService::SECTION_ALLOWED] as $routeData)
                        <tr>
                            <td>{{ implode(', ', $routeData['methods']) }}</td>
                            <td>{{ $routeData['path'] }}</td>
                            <td>{{ $routeData['name'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pure-g">
        <div class="pure-u-1-2">
            <div style="margin: 0 .5rem 0 1rem;">
                <h4>Защищенные маршруты ({{ count($routesData[IAclService::SECTION_PROTECTED]) }})</h4>
                <table class="pure-table pure-table-horizontal pure-table-striped">
                    <thead>
                    <tr>
                        <th style="width: 80px;">Методы</th>
                        <th>Путь</th>
                        <th>Имя</th>
                        <th>Разрешение</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routesData[IAclService::SECTION_PROTECTED] as $routeData)
                        <tr>
                            <td>{{ implode(', ', $routeData['methods']) }}</td>
                            <td>{{ $routeData['path'] }}</td>
                            <td>{{ $routeData['name'] }}</td>
                            <td>
                                @if (!is_array($routeData['permissionData']))
                                    {{ $routeData['permissionData'] }}
                                @else
                                    {!! implode('<br/>', $routeData['permissionData']['permissions']) !!}<br/>
                                    @if (count($routeData['permissionData']['permissions']) > 1)
                                        <strong>({{ $routeData['permissionData']['type'] ?? IAclService::CHECK_TYPE_AND }})</strong>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pure-u-1-2">
            <div style="margin: 0 1rem 0 .5rem;">
                <h4>Свободные маршруты ({{ count($routesData[IAclService::SECTION_UNSAFE]) }})</h4>
                <table class="pure-table pure-table-horizontal pure-table-striped">
                    <thead>
                    <tr>
                        <th style="width: 80px;">Методы</th>
                        <th>Путь</th>
                        <th>Имя</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routesData[IAclService::SECTION_UNSAFE] as $routeData)
                        <tr>
                            <td>{{ implode(', ', $routeData['methods']) }}</td>
                            <td>{{ $routeData['path'] }}</td>
                            <td>{{ $routeData['name'] }}</td>
                            <td>{{ $routeData['action'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection