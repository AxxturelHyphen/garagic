{{-- Sello «IA»: marca TODO contenido generado por IA. Es el único uso del bermellón en toda la app (el color está reservado a esto); cuadrado, con filete interior y un leve giro, como un sello de tinta. --}}
@props(['size' => 'sm', 'label' => false])
<span {{ $attributes->class('inline-flex items-center gap-2') }}>
    <span class="ai-seal ai-seal--{{ $size }}" role="img" aria-label="Contenido generado por IA" title="Contenido generado por IA">IA</span>
    @if ($label)
        <span class="text-graphite">Generado por IA</span>
    @endif
</span>
