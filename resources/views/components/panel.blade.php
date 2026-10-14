{{-- Bloque de contenido. plain: solo un filete de pelo arriba (lo normal, escrito sobre el papel). raised: caja de cartulina con filete de tinta (formularios). double: raised + segundo filete interior (rasgo RWS). Usar poco. --}}
@props(['title' => null, 'variant' => 'plain', 'as' => 'section'])
@php
    $classes = match ($variant) {
        'raised', 'double' => 'border border-ink bg-card p-5 md:p-6',
        default => 'border-t border-hairline pt-6',
    };
@endphp
<{{ $as }} {{ $attributes->class($classes) }} @if ($variant === 'double') style="outline: 1px solid var(--ink); outline-offset: -5px" @endif>
    @if ($title || (isset($actions) && $actions->isNotEmpty()))
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @if ($title)
                <h2 class="font-display text-2xl">{{ $title }}</h2>
            @endif

            @if (isset($actions) && $actions->isNotEmpty())
                <div class="flex flex-wrap gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif

    {{ $slot }}
</{{ $as }}>
