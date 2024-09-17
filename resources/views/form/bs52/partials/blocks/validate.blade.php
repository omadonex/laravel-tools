@php
    /** @var bool   $cmpNoValidate */
    /** @var string $cmpValidate` */
@endphp

@isset($cmpNoValidate)
    data-jst-no-validate="true"
@else
    @isset($cmpValidate)
        data-jst-validate="{{ $cmpValidate }}"
    @endisset
@endisset