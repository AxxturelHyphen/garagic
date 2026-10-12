{{-- Línea del ticket: etiqueta, puntos guía y valor (mono). --}}
@props(['label'])
<div class="flex items-baseline gap-2">
    <dt>{{ $label }}</dt>
    <span class="mb-1 flex-1 border-b border-dotted border-rule" aria-hidden="true"></span>
    <dd class="font-mono text-sm tabular-nums">{{ $slot }}</dd>
</div>
