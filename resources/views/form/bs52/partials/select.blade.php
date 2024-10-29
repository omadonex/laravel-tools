@php
    /** @var $cmpName */
    /** @var $cmpPlaceholder */

    $cmpList = isset($cmpList) ? $cmpList: [];
    $current = old($cmpName) ?: (isset($cmpValue) ? $cmpValue : null);

    if (isset($cmpPlaceholder)) {
        $blockPlaceholder = $cmpPlaceholder;
    } else {
        $blockPlaceholderKey = isset($cmpPlaceholderKey) ? $cmpPlaceholderKey : $cmpName;
        $blockPlaceholder = __("validation.attributes.{$blockPlaceholderKey}");
    }

    $options = [
        'placeholder' => $blockPlaceholder,
        'allowEmptyOption' => $allowEmptyOption ?? true,
    ];

    if (!isset($cmpSearch) || !$cmpSearch) {
        $options['controlInput'] = null;
        $options['plugins'] = ['no_backspace_delete'];
    }

    if (isset($cmpHideSelected)) {
        $options['hideSelected'] = $cmpHideSelected;
    }

    if (isset($cmpMultiple)) {
        $options['plugins'] = array_merge($options['plugins'] ?? [], ['remove_button']);
    }
@endphp

@include('omx-form::bs52.partials.blocks.label')
<select
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.placeholder')
    @include('omx-form::bs52.partials.blocks.validate')
    @isset($cmpMultiple) multiple @endisset
    class="form-select {{ $cmpClass ?? '' }} {{ count($errors->get($cmpName)) ? 'is-invalid' : '' }}"
    autocomplete="off"
    data-select="{{ json_encode($options) }}"
    @isset($cmpData)
        @foreach($cmpData as $key => $value)
            data-{{ $key }}="{{ $value }}"
        @endforeach
    @endisset
>
    @foreach($cmpList as $key => $item)
        <option @if (isset($cmpMultiple) ? in_array($key, $value ?? []) : $key == $current) selected @endif value="{{ $key }}">{!! $item !!}</option>
    @endforeach
</select>
@include('omx-form::bs52.partials.blocks.errors')