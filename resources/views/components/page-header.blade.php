{{-- Cabecera de página: título caligráfico (con numeral romano en los vehículos), enlace de vuelta opcional y acciones a la derecha. Sin subtítulo: el título basta. --}}
@props(['title', 'numeral' => null, 'back' => null])
<header {{ $attributes->class('mb-8') }}>
    @if ($back)
        <a href="{{ $back['href'] }}" wire:navigate class="mb-3 inline-flex items-center gap-1 text-sm text-graphite hover:text-ink">
            <flux:icon.arrow-left variant="micro" />{{ $back['label'] }}
        </a>
    @endif

    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-hairline pb-4">
        <h1 class="font-display text-3xl tracking-display md:text-4xl">
            @if ($numeral)<span class="me-3 text-graphite">{{ $numeral }}</span>@endif{{ $title }}
        </h1>

        @if (isset($actions) && $actions->isNotEmpty())
            <div class="flex flex-wrap gap-2">{{ $actions }}</div>
        @endif
    </div>
</header>
