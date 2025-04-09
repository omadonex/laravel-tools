@php
    /** @var array $options */

    $pageId = $options['page']['id'];
    $pageTab = $options['page']['tab'];

    $model = $options['page']['model'];
@endphp

@extends('omx-bootstrap::resource.show.template')

@section('show-tab-buttons')
    @include('root.acl.user.tab._button.role')
@endsection

@section('show-tab-main-extContent')
    <div class="col-lg-4 py-5">
        <div class="card border-primary mb-3">
            <div class="card-header"><h4>Смена пароля</h4></div>
            <div class="card-body">
                @include('root.acl.user._formChangePass', ['formId' => "{$pageId}__formChangePass", 'method' => 'POST', 'action' => route('root.acl.user.pass.store', $model->getKey())])
            </div>
        </div>
    </div>
@endsection

@section('show-tab-content')
    @include('root.acl.user.tab.role')
@endsection
