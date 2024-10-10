@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block px-4 py-2 text-sm leading-5 text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition'
                : 'block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition';

    $style = ($active ?? false)
        ? "text-decoration: underline; text-decoration-color: rgb(248, 113, 113); text-underline-offset: 10px; text-decoration-thickness: 2px;"

        :
            "";
@endphp

<a {{ $attributes->merge(['class' => $classes, 'style' => $style]) }}> {{ $slot }} </a>
