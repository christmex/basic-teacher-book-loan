{{-- livewire_select_field field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
@endphp

@include('crud::fields.inc.wrapper_start')
    <label class="@error($field['name']) text-danger @enderror">{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <livewire:fields.livewire-select :field="$field" />

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

