@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">@include('omx-form::bs52.partials.required'){{ $label }}</label>
@endif
{!! $treeData['buttonsHtml'] !!}
<div id="{{ $id }}" class="{{ $class ?? '' }}" style="{{ $style ?? '' }}" data-jst-field="{{ $name }}" data-jst-component="jstree" data-value="{{ $value ?? null }}"
    @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
    {!! $treeData['bodyHtml'] !!}
</div>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>