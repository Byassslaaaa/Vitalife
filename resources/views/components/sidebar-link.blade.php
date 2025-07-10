@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex items-center p-2 text-white bg-gray-900'
            : 'flex items-center p-2 text-gray-300 hover:bg-gray-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
