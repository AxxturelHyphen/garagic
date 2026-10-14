{{-- Cifra con su etiqueta. mono = dato de recibo (km, €); display = número grande en caligráfica (recuentos, vitalidad). --}}
@props(['label', 'value', 'variant' => 'mono', 'hint' => null])
<div {{ $attributes }}>
    <p class="text-graphite">{{ $label }}</p>
    <p @class(['font-display text-4xl leading-tight' => $variant === 'display', 'whitespace-nowrap font-mono text-xl tabular-nums sm:text-2xl' => $variant !== 'display'])>{{ $value }}</p>
    @if ($hint)
        <p class="text-graphite">{{ $hint }}</p>
    @endif
</div>
