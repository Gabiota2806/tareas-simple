@props([
    'type' => 'info'
])

@php
$classes = match($type) {
    'success' => 'bg-green-100 text-green-700 border-green-300',
    'error' => 'bg-red-100 text-red-700 border-red-300',
    'warning' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
    default => 'bg-blue-100 text-blue-700 border-blue-300',
};
@endphp

<div
    {{ $attributes->merge([
        'class' => "rounded-xl border px-4 py-3 $classes"
    ]) }}
>
    {{ $slot }}
</div>