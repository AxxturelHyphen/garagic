{{--
    Carta de tarot: la pieza memorable. Proporción 7:12 (Rider-Waite-Smith), margen de cartulina,
    doble filete, numeral romano arriba y nombre abajo; en el dorso, los datos secundarios.
    Gira con clic/teclado, se inclina con el cursor y se desgasta con la vitalidad. La mecánica
    visual está en resources/css/card.css y el estado (girada, inclinación) en resources/js/garagic/cards.js.

    PROPS
      card        array   numeral, title, plate?, back?  y UNO de estos para el arte:
                          engraving (nombre de un grabado de public/img/engravings, sin extensión)
                          photo     (URL de una foto con fondo blanco, p. ej. '/images/seat-ibiza-2026.webp')
                          arcana    (URL de un escaneo RWS de public/img/arcana; o ['src', 'w', 'h', 'crop' => [izq, arriba, der, abajo]]
                                     para dar el recorte en píxeles del escaneo)
                          icon      (nombre de un icono Lucide, respaldo si no hay imagen)
                          back = ['Etiqueta' => 'valor', …]  datos del dorso (km, matrícula, ITV)
      vitality    int     0-100, default 100. SOLO controla el desgaste (la lógica real vive en otra clase)
      empty       bool    «Sin datos»: arte fantasma y rótulo cruzado
      size        string  sm (9rem) | md (14rem) | lg (18-20rem) | fluid (ancho del contenedor, default)
      flippable   bool    ¿botón que gira la carta? (true). Con false no hay botón: la carta es decorativa
      flipped     bool    ¿empieza con el dorso a la vista? (false)
      tilt        bool    ¿se inclina con el cursor? (true; nunca en táctil ni con «reducir movimiento»)
      entrance    bool    reparto de la portada: llega boca abajo y se gira (700 ms)
      reveal      bool    «sacar carta» del Oráculo: del dorso al frente (300 ms)
      name        string  si se da, la carta escucha el evento `tarot-flip` con ese nombre:
                          x-on:click="$dispatch('tarot-flip', 'mi-nombre')" desde un botón externo la gira
      deck-index  int     solo lo pone <x-tarot-deck>: la carta va en una mano (pulsarla la saca al frente)

    SLOT
      stamp       un <x-achievement-stamp size="mark"> que se estampa sobre la carta
--}}
@props([
    'card',
    'vitality' => 100,
    'empty' => false,
    'size' => 'fluid',
    'flippable' => true,
    'flipped' => false,
    'tilt' => true,
    'entrance' => false,
    'reveal' => false,
    'name' => null,
    'deckIndex' => null,
])
@php
    $width = match ($size) {
        'sm' => 'w-36',
        'md' => 'w-56',
        'lg' => 'w-72 sm:w-80',
        default => 'w-full',
    };
    $inDeck = $deckIndex !== null;
    $interactive = $flippable || $tilt || $inDeck;
    $label = $card['title'].', carta '.$card['numeral'];
@endphp
<div {{ $attributes->class(['tc', $width, 'tc--deal' => $entrance, 'tc--reveal' => $reveal]) }}
     style="--vitality: {{ (int) $vitality }}; --corner-art: url('{{ asset('img/ornament/corner.webp') }}')"
     data-flipped="{{ $flipped ? 'true' : 'false' }}"
     @if ($empty) data-empty @endif
     @if ($inDeck) data-deck-index="{{ $deckIndex }}" @endif
     @if ($interactive)
         x-data="tarotCard({ flipped: @js($flipped), tilt: @js($tilt), index: @js($deckIndex), label: @js($card['title']) })"
         x-bind:data-flipped="flipped ? 'true' : 'false'"
         x-on:pointermove="tiltTo($event)"
         x-on:pointerleave="tiltReset()"
         @if ($name) x-on:tarot-flip.window="$event.detail === @js($name) && flip()" @endif
     @endif>
    @if ($flippable || $inDeck)
        <button type="button" class="tc__hit"
                x-on:click="flip()"
                x-bind:aria-label="hitLabel"
                x-bind:aria-pressed="pressed"
                aria-label="{{ $inDeck ? 'Carta: '.$card['title'] : 'Girar la carta: '.$card['title'] }}"></button>
    @endif

    <div class="tc__tilt" aria-hidden="true">
        <div class="tc__inner">
            <div class="tc__face tc__face--front">
                <div class="tc__paper">
                    <div class="tc__frame">
                        <div class="tc__frame-inner">
                            <div class="tc__numeral">{{ $card['numeral'] }}</div>

                            <div class="tc__window">
                                <span class="tc__rays"></span>
                                <span class="tc__ground"></span>

                                @if (! empty($card['engraving']))
                                    <x-engraving :name="$card['engraving']" class="tc__art" />
                                @elseif (! empty($card['photo']))
                                    <span class="tc__vignette"></span>
                                    <img class="tc__photo" src="{{ asset(ltrim($card['photo'], '/')) }}" alt="" draggable="false" decoding="async">
                                @elseif (! empty($card['arcana']))
                                    @php
                                        // El escaneo RWS trae margen, numeral y título en inglés: se enseña solo la zona buena
                                        // (píxeles [izq, arriba, der, abajo] del escaneo). Sin recorte propio, el de los arcanos de public/img/arcana.
                                        [$arcanaSrc, $arcanaW, $arcanaH, $arcanaCrop] = is_array($card['arcana'])
                                            ? [$card['arcana']['src'], $card['arcana']['w'] ?? 520, $card['arcana']['h'] ?? 898, $card['arcana']['crop'] ?? [26, 66, 488, 744]]
                                            : [$card['arcana'], 520, 898, [26, 66, 488, 744]];
                                        [$cropLeft, $cropTop, $cropRight, $cropBottom] = $arcanaCrop;
                                    @endphp
                                    <span class="tc__arcana-frame"
                                          style="--s: {{ round($arcanaW / $arcanaH, 5) }}; --l: {{ round($cropLeft / $arcanaW, 5) }}; --t: {{ round($cropTop / $arcanaH, 5) }}; --rw: {{ round(($cropRight - $cropLeft) / $arcanaW, 5) }}; --rh: {{ round(($cropBottom - $cropTop) / $arcanaH, 5) }}">
                                        <img class="tc__arcana" src="{{ asset(ltrim($arcanaSrc, '/')) }}" alt="" draggable="false" decoding="async">
                                    </span>
                                @elseif (! empty($card['icon']))
                                    <flux:icon :icon="$card['icon']" class="tc__icon" />
                                @endif

                                <span class="tc__corner tc__corner--tl"></span>
                                <span class="tc__corner tc__corner--tr"></span>
                                <span class="tc__corner tc__corner--bl"></span>
                                <span class="tc__corner tc__corner--br"></span>
                            </div>

                            <div class="tc__title">{{ $card['title'] }}.</div>

                            @isset($card['plate'])
                                <div class="tc__plate">{{ $card['plate'] }}</div>
                            @endisset
                        </div>
                    </div>

                    <div class="tc__grain"></div>
                    <div class="tc__stains"></div>

                    @if ($empty)
                        <div class="tc__nodata">Sin datos</div>
                    @endif
                </div>

                <div class="tc__glare"></div>

                @isset($stamp)
                    <div class="tc__stamp">{{ $stamp }}</div>
                @endisset
            </div>

            <div class="tc__face tc__face--back">
                <x-card-back :data="$card['back'] ?? null" />
            </div>
        </div>
    </div>

    {{-- Lo que ven los lectores de pantalla: las caras son decorativas (aria-hidden). --}}
    <p class="sr-only">{{ $label }}.@foreach (($card['back'] ?? []) as $key => $value) {{ $key }}: {{ $value }}.@endforeach @if ($empty) Sin datos.@endif</p>
</div>
