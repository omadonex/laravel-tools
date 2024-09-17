@extends('layouts.app')

@section('app-content')
    <div class="row">
        <div class="col-xl-12 d-flex" style="padding-left: unset; padding-right: unset;">
            @include('omx-bootstrap::table.template', [
                'page' => $options['page'],
                'table' => $options['tableList'][0],
                'modalParams' => [],
            ])
        </div>
    </div>
@endsection
