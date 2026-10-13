{{--
    Logros: el muro de sellos. Ganados (con su fecha) y bloqueados (filete discontinuo y lo que falta).
    Datos de resources/views/prototype/_fake-data.php ($achievements, $emblems). Sin introducción: los sellos hablan.
--}}
@php
    $earned = collect($achievements)->where('unlocked', true)->count();

    // Cada sello cae a una altura distinta para que el muro no parezca una rejilla de tarjetas.
    // En 2 columnas (sm) solo se desplaza el segundo de cada fila; en 4 (lg), cada uno a lo suyo.
    $offsets = ['lg:mt-0', 'sm:max-lg:mt-6 lg:mt-6', 'lg:mt-2', 'sm:max-lg:mt-6 lg:mt-8'];

    // Las cifras del texto van en mono (los dígitos de IM Fell son «de estilo antiguo»: el 1 parece una I).
    // Se escapa el texto y se envuelve cada número sin tocar las entidades HTML (&#039; contiene dígitos).
    $mono = fn (?string $text): ?\Illuminate\Support\HtmlString => $text === null ? null : new \Illuminate\Support\HtmlString(
        preg_replace_callback(
            '/&#?\w+;|\d[\d.,\/]*\d|\d/u',
            fn (array $match): string => str_starts_with($match[0], '&') ? $match[0] : '<span class="font-mono text-[0.9em] tabular-nums">'.$match[0].'</span>',
            e($text),
        ),
    );
@endphp
<x-layouts::prototype title="Logros" :user="$user">
    <x-page-header title="Logros">
        <x-slot:actions>
            <x-stat label="Sellos" :value="$earned.' de '.count($achievements)" variant="display" class="text-right" />
        </x-slot:actions>
    </x-page-header>

    {{-- overflow-x-clip: el sello recién ganado arranca a escala 1,35 (stamp-in) y en móvil ensanchaba un instante la página (7 px de scroll horizontal). --}}
    <ul role="list" class="grid items-start gap-x-8 gap-y-10 overflow-x-clip sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($achievements as $achievement)
            @php $fresh = $achievement['unlocked'] && $achievement['fresh']; @endphp

            {{-- El recién ganado espera a que se vea para estamparse (ver achievements-wall.js). --}}
            <li class="{{ $offsets[$loop->index % 4] }}"
                @if ($fresh)
                    x-data="stampOnView"
                    data-stamp-wait
                    x-bind:data-stamp-wait="waiting ? '' : null"
                    x-intersect.once.threshold.60="release()"
                @endif>
                {{-- Bloqueado: el nombre ya dice qué se pide; la pista dice cuánto falta. Ganado: qué se hizo y cuándo. --}}
                <x-achievement-stamp
                    :name="$achievement['name']"
                    :description="$achievement['unlocked'] ? $achievement['description'] : null"
                    :emblem="$emblems[$achievement['key']] ?? null"
                    :icon="$achievement['icon']"
                    :unlocked="$achievement['unlocked']"
                    :date="$achievement['date']"
                    :hint="$mono($achievement['hint'])"
                    :fresh="$fresh"
                    :tilt="$achievement['tilt']"
                    class="mx-auto sm:mx-0" />
            </li>
        @endforeach
    </ul>
</x-layouts::prototype>
