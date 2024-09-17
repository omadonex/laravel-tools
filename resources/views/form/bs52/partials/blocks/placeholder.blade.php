@php
    /** @var string $cmpName */
    /** @var bool   $cmpNoPlaceholder */
    /** @var string $cmpPlaceholder */
    /** @var string $cmpPlaceholderKey */

    if (isset($cmpPlaceholder)) {
        $blockPlaceholder = $cmpPlaceholder;
    } else {
        $blockPlaceholderKey = isset($cmpPlaceholderKey) ? $cmpPlaceholderKey : $cmpName;
        $blockPlaceholder = __("validation.attributes.{$blockPlaceholderKey}");
    }
@endphp

@if (!isset($cmpNoPlaceholder) || !$cmpNoPlaceholder)
    placeholder="{{ $blockPlaceholder }}"
@endif