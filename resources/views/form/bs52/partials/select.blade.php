@php
    /** @var $name */
    /** @var $placeholder */
    $list = isset($list) ? $list: [];
    $current = old($name) ?: (isset($value) ? $value : null);
    $options = [
        'placeholder' => $placeholder,
        'allowEmptyOption' => $allowEmptyOption ?? true,
    ];

    if (!isset($search) || !$search) {
        $options['controlInput'] = null;
        $options['plugins'] = ['no_backspace_delete'];
    }

    if (isset($hideSelected)) {
        $options['hideSelected'] = $hideSelected;
    }

    if (isset($multiple)) {
        $options['plugins'] = array_merge($options['plugins'] ?? [], ['remove_button']);
    }
@endphp

@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">@include('omx-form::bs52.partials.required'){{ $label }}</label>
@endif
<select
    @isset($multiple) multiple @endisset
    @if(!empty($placeholder)) data-placeholder="{{$placeholder}}" @endisset
    id="{{ $id }}"
    name="{{ $name }}"
    class="form-select {{ $class ?? '' }} {{ count($errors->get($name)) ? 'is-invalid' : '' }}"
    autocomplete="off"
    data-select="{{ json_encode($options) }}"
    data-jst-field="{{ $name }}"
    @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset
>
    @foreach($list as $key => $item)
        <option @if (isset($multiple) ? in_array($key, $value ?? []) : $key == $current) selected @endif value="{{ $key }}">{{ $item }}</option>
    @endforeach
</select>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>