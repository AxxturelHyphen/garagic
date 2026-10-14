{{--
    La mano: una carta grande por vehículo, pegadas y solapadas en abanico. La carta al frente
    sube y se endereza; pulsar otra la saca al frente y las demás se reordenan (la que estaba
    delante pasa al final). Pulsar la que ya está al frente la gira. En móvil es una pila
    vertical: la activa arriba y las demás asoman por debajo con su nombre.
    Mecánica: resources/css/card.css (.hand) y resources/js/garagic/cards.js (tarotDeck).

    Pensado para que <x-garage-board> (WP2.3) lo componga encima.

    PROPS
      cards     lista de ['card' => [...], 'vitality' => int, 'empty' => bool, 'label' => 'Seat Ibiza', 'stamp' => [...]?]
                - card, vitality, empty: exactamente los de <x-tarot-card> (ver su cabecera)
                - label: nombre accesible de la carta (botón y aviso de lectores de pantalla)
                - stamp (opcional): ['name', 'emblem'?, 'icon'?, 'tilt'?, 'fresh'?] sello de logro estampado sobre la carta
      active    índice de la carta que empieza al frente (0)
      entrance  bool: reparto de la mano al cargar (true; hasta ~700 ms, estático con «reducir movimiento»)
      label     nombre accesible de la lista ('Tu baraja')

    SLOT
      aside     contenido que va DEBAJO de la mano y depende de la carta activa. Dentro se puede usar
                el estado de Alpine de la mano: x-show="isActive(0)", active, count, pick(i), next(), prev().
                Cada panel: <div x-show="isActive(2)" x-cloak x-transition.opacity.duration.200ms>…</div>

    EVENTO
      tarot-deck:change  ({ index }) cada vez que cambia la carta al frente; sube por el DOM,
                         se escucha con x-on:tarot-deck:change="…" en un ancestro.

    TECLADO: con el foco en una carta, flechas, Inicio y Fin cambian de carta; Enter/Espacio sacan
    al frente o giran. Los botones de debajo hacen lo mismo con el ratón o el dedo.
--}}
@props([
    'cards',
    'active' => 0,
    'entrance' => true,
    'label' => 'Tu baraja',
])
@php
    $count = count($cards);
    $cards = array_values($cards);
    // Giro fijo de cada carta, entre ±0,75° y ±2,25°: una mano de cartas, no una cuadrícula.
    $tilts = ['-1.75deg', '1.25deg', '-0.75deg', '2.25deg', '-1.25deg', '0.75deg'];
    $labels = array_map(fn (array $item): string => $item['label'] ?? $item['card']['title'], $cards);
@endphp
<div {{ $attributes }}
     x-data="tarotDeck({ active: {{ (int) $active }}, count: {{ $count }} })"
     data-tarot-deck>
    <div @class(['hand', 'hand--deal' => $entrance])>
        <ul class="hand__row" role="list" aria-label="{{ $label }}"
            style="--n: {{ $count }}"
            x-on:keydown="onKeydown($event)">
            @foreach ($cards as $i => $item)
                <li class="hand__slot"
                    style="--i: {{ $i }}; --depth: {{ ($i - $active + $count) % $count }}; --tilt: {{ $tilts[$i % count($tilts)] }}"
                    x-effect="$el.style.setProperty('--depth', depth({{ $i }}))"
                    x-bind:data-active="isActive({{ $i }}) ? '' : null"
                    @if ($i === (int) $active) data-active @endif>
                    <x-tarot-card
                        :card="$item['card']"
                        :vitality="$item['vitality'] ?? 100"
                        :empty="$item['empty'] ?? false"
                        :deck-index="$i"
                        :tilt="true"
                        size="fluid">
                        @if (! empty($item['stamp']))
                            <x-slot:stamp>
                                <x-achievement-stamp size="mark" :unlocked="true" :fresh="$item['stamp']['fresh'] ?? false" :name="$item['stamp']['name']" :emblem="$item['stamp']['emblem'] ?? null" :icon="$item['stamp']['icon'] ?? 'stamp'" :tilt="$item['stamp']['tilt'] ?? '-6deg'" />
                            </x-slot:stamp>
                        @endif
                    </x-tarot-card>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($count > 1)
        <div class="mt-6 flex items-center justify-center gap-4">
            <flux:button variant="ghost" size="sm" icon="chevron-left" x-on:click="prev(); focusActive()" aria-label="Carta anterior" />
            <span class="font-mono text-sm tabular-nums"><span x-text="active + 1">{{ $active + 1 }}</span> / {{ $count }}</span>
            <flux:button variant="ghost" size="sm" icon="chevron-right" x-on:click="next(); focusActive()" aria-label="Carta siguiente" />
        </div>
    @endif

    {{-- Avisa a los lectores de pantalla de qué carta ha quedado al frente. --}}
    <p class="sr-only" aria-live="polite" x-text="{{ \Illuminate\Support\Js::from($labels) }}[active]">{{ $labels[$active] ?? '' }}</p>

    @isset($aside)
        <div class="mt-8">{{ $aside }}</div>
    @endisset
</div>
