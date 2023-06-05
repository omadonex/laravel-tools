@if ($errors->hasBag($formId))
    <div data-jst-alert class="alert alert-danger">
        <ul>
            @foreach ($errors->getBag($formId)->getMessages() as $key => $errorItem)
                @foreach($errorItem as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@else
    <div data-jst-alert class="alert d-none"></div>
@endif
