@props([
    'label' => '',
    'name',
    'type' => 'text',
    'placeholder' => ''
])

<div>
    @if($label)
        <label
            for="{{ $name }}"
            class="block text-sm font-medium text-gray-700 mb-2"
        >
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 shadow-sm outline-none transition focus:border-violeta-moderno focus:ring-violeta-moderno'
        ]) }}
    >
</div>