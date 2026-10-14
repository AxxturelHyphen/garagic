{{-- Estado vacío: un grabado, una frase corta (el título) y una acción. Alineado a la izquierda. --}}
@props(['title', 'engraving' => 'wrench-gates-1871'])
@php
    // Los grabados altos (llave, motor) piden menos ancho que los apaisados.
    $width = match ($engraving) {
        'wrench-gates-1871' => 'w-14',
        'engine-otto-1911' => 'w-32',
        default => 'w-40',
    };
@endphp
<div {{ $attributes->class('flex flex-col items-start gap-4 py-8') }}>
    <x-engraving :name="$engraving" class="{{ $width }} text-graphite" />

    <h2 class="font-display text-2xl">{{ $title }}</h2>

    @if ($slot->isNotEmpty())
        <p class="max-w-[50ch] text-graphite">{{ $slot }}</p>
    @endif

    @if (isset($actions) && $actions->isNotEmpty())
        <div class="flex flex-wrap gap-2">{{ $actions }}</div>
    @endif
</div>
