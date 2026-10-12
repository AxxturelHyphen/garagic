{{--
    Sello de logro: un emblema de grabado real (máscara de tinta) en un marco de doble filete. Se estampa
    al aparecer si fresh (250 ms, scale + leve giro). El estado va en texto («Ganado el…» / «Bloqueado»).
    PROPS: name, description, emblem (URL del emblema; si falta, icono Lucide), icon ('stamp'), unlocked, date (Y-m-d),
    hint (qué falta, para bloqueados), fresh (anima), tilt ('-3deg'), size: md | sm | mark.
    size="mark" es solo el emblema en una placa, para estamparlo sobre una carta (slot `stamp` de <x-tarot-card>).
--}}
@props([
    'name',
    'description' => null,
    'emblem' => null,
    'icon' => 'stamp',
    'unlocked' => true,
    'date' => null,
    'hint' => null,
    'fresh' => false,
    'tilt' => '0deg',
    'size' => 'md',
])
<article {{ $attributes->class(['stamp', 'stamp--'.$size]) }}
         style="--stamp-tilt: {{ $unlocked ? $tilt : '0deg' }}"
         @if (! $unlocked) data-locked @endif
         @if ($fresh && $unlocked) data-fresh @endif>
    @if ($emblem)
        <span class="stamp__emblem" style="--emblem: url('{{ asset(ltrim($emblem, '/')) }}')" role="img" aria-label="{{ $name }}"></span>
    @else
        <flux:icon :icon="$unlocked ? $icon : 'circle-dashed'" class="size-8" aria-label="{{ $name }}" />
    @endif

    @if ($size === 'mark')
        <span class="sr-only">Sello: {{ $name }}</span>
    @else
        <h3 class="font-display text-xl leading-tight">{{ $name }}</h3>

        @if ($description)
            <p class="text-graphite">{{ $description }}</p>
        @endif

        @if ($unlocked)
            @if ($date)
                <p class="text-graphite">Ganado el <x-date :value="$date" /></p>
            @endif
        @else
            <x-status-tag tone="empty">Bloqueado</x-status-tag>
            @if ($hint)
                <p class="text-graphite">{{ $hint }}</p>
            @endif
        @endif
    @endif
</article>
