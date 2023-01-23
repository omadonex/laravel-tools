@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
@endif
<div class="input-group has-validation">
    <input type="password" id="{{ $id }}" name="{{ $name }}" class="form-control" autocomplete="off" data-toggle-password-input placeholder="{{ $placeholder }}"
           data-jst-field="{{ $name }}" @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
    <button type="button" class="input-group-text px-4 text-secondary link-primary" data-toggle-password tabindex="-1"></button>
    <div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>
</div>
