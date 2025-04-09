<div class="tab-pane fade px-5 @if ($pageTab == 'role') show active @endif" id="{{ $pageId }}__tabCard__paneRole" role="tabpanel" aria-labelledby="{{ $pageId }}__tabCard__buttonRole" tabindex="0">
    <div class="row">
        <div class="col-lg-4 py-5">
            <input id="{{ $pageId }}__TableUserRoles_urlRowDelete" type="hidden" value="{{ route("root.acl.user.role.destroy", $model->getKey()) }}"/>
            <table id="{{ $pageId }}__TableUserRoles" class="table table-striped table-condensed">
                <tbody>
                @foreach($userRoleList as $key => $name)
                    <tr>
                        <td>{{ $name }}</td>
                        <td style="text-align: right">{!! rowDeleteIcon($key) !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-4 py-5">
            <div class="card border-primary mb-3">
                <div class="card-header"><h4>Назначение роли</h4></div>
                <div class="card-body">
                    @include('root.acl.user._formAttachRole', ['formId' => "{$pageId}__formAttachRole", 'method' => 'POST', 'action' => route('root.acl.user.role.store', $model->getKey())])
                </div>
            </div>
        </div>
    </div>
</div>
