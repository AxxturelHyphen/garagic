{{--
    Respuesta del Oráculo: un arcano real (escaneo RWS de 1909, unificado con la paleta por CSS y con
    el nombre en español) que dice la urgencia, al lado su texto literal y el sello «IA».
    La urgencia elige el arcano: baja = La Estrella (XVII), media = La Rueda de la Fortuna (X), alta = La Torre (XVI).
    PROPS: urgency (low | medium | high), summary, question (eco de la consulta, opcional), reveal (true: se da la vuelta).
    SLOTS: por defecto (causas probables), advice (qué hacer), proposals (entradas propuestas).
--}}
@props(['urgency' => 'low', 'summary', 'question' => null, 'reveal' => true])
@php
    // Decisión de diseño: el arcano es la forma de decir la urgencia sin rojo ni verde.
    // crop = zona buena del escaneo en píxeles [izq, arriba, der, abajo]: sin margen, numeral ni título en inglés.
    $arcana = [
        'low' => ['numeral' => 'XVII', 'title' => 'La Estrella', 'arcana' => ['src' => '/img/arcana/17-la-estrella.webp', 'w' => 520, 'h' => 896, 'crop' => [24, 50, 484, 770]], 'tone' => 'calm', 'level' => 'Urgencia baja', 'meaning' => 'Calma: nada urgente.'],
        'medium' => ['numeral' => 'X', 'title' => 'La Rueda de la Fortuna', 'arcana' => ['src' => '/img/arcana/10-rueda-de-la-fortuna.webp', 'w' => 520, 'h' => 904, 'crop' => [26, 66, 490, 787]], 'tone' => 'medium', 'level' => 'Urgencia media', 'meaning' => 'Atento: puede ir a más.'],
        'high' => ['numeral' => 'XVI', 'title' => 'La Torre', 'arcana' => ['src' => '/img/arcana/16-la-torre.webp', 'w' => 520, 'h' => 900, 'crop' => [26, 66, 488, 744]], 'tone' => 'strong', 'level' => 'Urgencia alta', 'meaning' => 'Para y revisa: afecta a la seguridad.'],
    ][$urgency];
    $titleId = 'arcana-'.substr(md5($summary), 0, 8);
@endphp
<article {{ $attributes->class('border border-ink bg-card p-5 md:p-6') }} aria-labelledby="{{ $titleId }}">
    <header class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <x-ai-seal size="md" />
            <h2 id="{{ $titleId }}" class="font-display text-2xl">Respuesta del Oráculo</h2>
        </div>
        <x-status-tag :tone="$arcana['tone']">{{ $arcana['level'] }}</x-status-tag>
    </header>

    <div class="mt-5 grid gap-6 md:grid-cols-[11rem_minmax(0,1fr)]">
        <div class="mx-auto w-44 md:w-full">
            <x-tarot-card size="fluid"
                          :card="['numeral' => $arcana['numeral'], 'title' => $arcana['title'], 'arcana' => $arcana['arcana']]"
                          :flippable="false" :tilt="false" :reveal="$reveal" />
        </div>

        <div class="grid content-start gap-4">
            @if ($question)
                <p class="max-w-[65ch] italic text-graphite">{{ $question }}</p>
            @endif

            <p class="max-w-[65ch]">{{ $arcana['meaning'] }} {{ $summary }}</p>

            {{ $slot }}

            @isset($advice)
                {{ $advice }}
            @endisset
        </div>
    </div>

    @isset($proposals)
        <div class="mt-6">{{ $proposals }}</div>
    @endisset

    <p class="mt-6 border-t border-hairline pt-4 text-graphite">Puede equivocarse: si afecta a frenos, dirección o neumáticos, ve a un taller.</p>
</article>
