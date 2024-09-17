@php
    /** @var bool $cmpChecked */
    /** @var bool $cmpDefault */
@endphp

@isset($cmpChecked)
    @if ($cmpChecked === true) checked @endif
@else
    @if (isset($cmpDefault) && $cmpDefault) checked @endif
@endisset