<div class="form-check form-switch">
    <input type="checkbox" class="form-check-input {{ $class ?? '' }}" id="{{ $id }}" name="{{ $name }}" role="switch"
           @if(isset($checked) && $checked === true) checked @endif
           data-jst-field="{{ $name }}" @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
</div>