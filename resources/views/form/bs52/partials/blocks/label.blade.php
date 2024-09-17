@php
    /** @var string $cmpId */
    /** @var string $cmpName */
    /** @var bool   $cmpNoLabel */
    /** @var string $cmpLabel */
    /** @var string $cmpLabelKey */
    /** @var bool   $cmpCheck */

    if (isset($cmpLabel)) {
        $blockLabel = $cmpLabel;
    } else {
        $blockLabelKey = isset($cmpLabelKey) ? $cmpLabelKey : $cmpName;
        $blockLabel = __("validation.attributes.{$blockLabelKey}");
    }
@endphp

@if (!isset($cmpNoLabel) || !$cmpNoLabel)
    <label for="{{ $cmpId }}" class="@isset($cmpCheck) form-check-label @else form-label @endisset">
        @include('omx-form::bs52.partials.blocks.required')
        {{ $blockLabel }}
    </label>
@endif