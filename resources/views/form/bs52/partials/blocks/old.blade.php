@php
    /** @var string $cmpName */
    /** @var mixed $cmpValue */
@endphp

@if (old($cmpName))
    value="{{ old($cmpName) }}"
@elseif(isset($cmpValue))
    value="{{ $cmpValue }}"
@endif