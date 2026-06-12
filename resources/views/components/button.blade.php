@props([
    'type' => 'button'
])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'rounded-xl bg-violeta-moderno px-5 py-3 text-white font-semibold shadow-md transition hover:-translate-y-0.5 hover:shadow-lg'
    ]) }}
>
    {{ $slot }}
</button>