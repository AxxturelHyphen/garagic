{{--
    Marco de las pantallas de acceso (entrar, crear cuenta, recuperar, verificar). Misma cabecera de libreta
    y mismo pie que el resto de la app, pero la cabecera solo lleva la marca (los enlaces de acceso ya están
    en el formulario). A la izquierda, en escritorio, una carta real del tarot RWS como pieza protagonista;
    a la derecha, el formulario sobre un panel de cartulina opaco (así se lee por encima del fondo de prueba).

    PROPS
      title   texto del <title>
      art     qué arcano se muestra: carro (entrar), estrella (crear cuenta), rueda (recuperar contraseña)
--}}
@props(['title' => null, 'art' => 'carro'])
@php
    // Escaneos RWS de 1909 (dominio público, ver public/img/FUENTES.md). Cada pantalla de acceso lleva el suyo.
    $arcana = [
        'carro' => ['numeral' => 'VII', 'title' => 'El Carro', 'arcana' => '/img/arcana/07-el-carro.webp'],
        'estrella' => ['numeral' => 'XVII', 'title' => 'La Estrella', 'arcana' => '/img/arcana/17-la-estrella.webp'],
        'rueda' => ['numeral' => 'X', 'title' => 'La Rueda de la Fortuna', 'arcana' => '/img/arcana/10-rueda-de-la-fortuna.webp'],
    ][$art] ?? null;
@endphp
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    @include('partials.head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-card focus:px-3 focus:py-2">Saltar al contenido</a>

    <header>
        <div class="mx-auto flex h-16 max-w-6xl items-center px-4 md:px-8">
            <x-app-logo :href="route('home')" wire:navigate />
        </div>

        {{-- Doble filete: dos líneas de 1 px con 3 px de aire (rasgo de las cartas RWS). --}}
        <div class="border-b border-ink"></div>
        <div class="mt-[3px] border-b border-hairline"></div>
    </header>

    <main id="contenido" class="auth-main mx-auto w-full max-w-6xl flex-1 px-4 py-10 md:px-8">
        {{-- Solo decorativa: aria-hidden y sin botón de giro, así no añade paradas de tabulador ni ruido a los lectores de pantalla. --}}
        @if ($arcana)
            <div class="auth-art" aria-hidden="true">
                <x-tarot-card :card="$arcana" size="lg" :flippable="false" class="-rotate-3" />
            </div>
        @endif

        <div class="auth-form">
            <x-panel variant="double" as="div">
                {{ $slot }}
            </x-panel>
        </div>
    </main>

    <x-site-footer :prototype="app()->isLocal()" />

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
