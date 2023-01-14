@if ($errors->any())
    <div data-jst-alert class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@else
    <div data-jst-alert class="alert d-none"></div>
@endif
