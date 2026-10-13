{{--
    Mapa del prototipo: todas las pantallas en una lista de enlaces agrupada, con su ruta en mono.
    Sin descripciones: sirve para saltar de una a otra. Los nombres de los vehículos salen de los datos falsos.
--}}
@php
    // [etiqueta, nombre de ruta, parámetros]. La ruta se pinta relativa (route(..., absolute: false)).
    $groups = [
        'Público' => [
            ['Portada', 'home'],
            ['Error 403', 'prototype.errors.403'],
            ['Error 404', 'prototype.errors.404'],
            ['Error 500', 'prototype.errors.500'],
        ],
        'Acceso' => [
            ['Entrar', 'prototype.auth.login'],
            ['Registro', 'prototype.auth.register'],
            ['Olvidé la clave', 'prototype.auth.forgot'],
            ['Restablecer', 'prototype.auth.reset'],
            ['Verificar correo', 'prototype.auth.verify'],
        ],
        'App' => [
            ['Baraja', 'prototype.deck'],
            ['Rituales', 'prototype.rituals'],
            ['Oráculo', 'prototype.oracle'],
            ['Logros', 'prototype.achievements'],
            ['Nuevo vehículo', 'prototype.vehicles.create'],
        ],
        'Admin' => [
            ['Catálogo', 'prototype.admin'],
        ],
        'Ajustes' => [
            ['Perfil', 'prototype.settings.profile'],
            ['Seguridad', 'prototype.settings.security'],
            ['Apariencia', 'prototype.settings.appearance'],
        ],
        'Sistema' => [
            ['Guía de estilo', 'prototype.style'],
        ],
    ];

    // Pantallas reales (las que ya cuelgan de Fortify, Livewire y el dashboard): se saltan las que no existan.
    $real = collect([
        ['Portada', 'home'],
        ['Entrar', 'login'],
        ['Registro', 'register'],
        ['Olvidé la clave', 'password.request'],
        ['Baraja', 'dashboard'],
        ['Ajustes', 'profile.edit'],
    ])->filter(fn (array $item): bool => Route::has($item[1]))->all();

    $path = fn (string $name, array $params = []): string => route($name, $params, false);
@endphp
<x-layouts::prototype title="Mapa del prototipo" :user="$user">
    <x-page-header title="Mapa del prototipo">
        <x-slot:actions>
            <span class="text-graphite">Hoy <x-date :value="$today" /></span>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-x-12 gap-y-10 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($groups as $title => $items)
            <section aria-labelledby="map-{{ Str::slug($title) }}">
                <h2 id="map-{{ Str::slug($title) }}" class="mb-2 text-2xl">{{ $title }}</h2>
                <ul role="list" class="border-t border-hairline">
                    @foreach ($items as [$label, $name])
                        <li>
                            <a href="{{ route($name) }}" class="map-link">
                                <span class="map-link__label">{{ $label }}</span>
                                <span class="map-link__path">{{ $path($name) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        {{-- Un vehículo por fila, con sus tres pantallas. --}}
        <section aria-labelledby="map-vehiculos" class="md:col-span-2 lg:col-span-2">
            <h2 id="map-vehiculos" class="mb-2 text-2xl">Vehículos</h2>
            <ul role="list" class="border-t border-hairline">
                @foreach ($vehicles as $vehicle)
                    <li class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1 border-b border-hairline py-2">
                        <span>{{ $vehicle['name'] }}</span>
                        <span class="flex flex-wrap gap-x-5">
                            <a href="{{ route('prototype.vehicles.show', $vehicle['id']) }}" class="underline">Ficha</a>
                            <a href="{{ route('prototype.vehicles.edit', $vehicle['id']) }}" class="underline">Editar</a>
                            <a href="{{ route('prototype.vehicles.history', $vehicle['id']) }}" class="underline">Historial</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>

        <section aria-labelledby="map-reales">
            <h2 id="map-reales" class="mb-2 text-2xl">Reales</h2>
            <ul role="list" class="border-t border-hairline">
                @foreach ($real as [$label, $name])
                    <li>
                        <a href="{{ route($name) }}" class="map-link">
                            <span class="map-link__label">{{ $label }}</span>
                            <span class="map-link__path">{{ $path($name) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
</x-layouts::prototype>
