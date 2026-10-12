{{--
    Documento HTML completo de la app (la real y el prototipo): cabecera de libreta con
    marca, navegación y menú de usuario; contenido; pie. Lo usan layouts/app y layouts/prototype,
    que solo le pasan los datos (usuario, enlaces) y así el aspecto vive en un único sitio.

    nav: lista de ['label', 'href', 'current'].
    logoutAction: URL a la que se hace POST para salir (app real); logoutHref: enlace GET (prototipo).
--}}
@props([
    'title' => null,
    'user' => null,
    'nav' => [],
    'settingsHref' => null,
    'logoutAction' => null,
    'logoutHref' => null,
    'prototype' => false,
])
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    @include('partials.head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-card focus:px-3 focus:py-2">Saltar al contenido</a>

    <header x-data="disclosure()" x-on:keydown.escape.window="close()">
        <div class="mx-auto flex h-16 max-w-6xl items-center gap-8 px-4 md:px-8">
            <x-app-logo :href="$nav[0]['href'] ?? route('home')" wire:navigate />

            <nav aria-label="Principal" class="hidden items-center gap-6 md:flex">
                @foreach ($nav as $item)
                    <x-nav-link :href="$item['href']" :current="$item['current']">{{ $item['label'] }}</x-nav-link>
                @endforeach
            </nav>

            <div class="ms-auto flex items-center gap-3">
                @if ($user)
                    <x-user-menu :user="$user" :settings-href="$settingsHref" :logout-action="$logoutAction" :logout-href="$logoutHref" />
                @endif

                <button type="button"
                        class="inline-flex cursor-pointer items-center gap-2 border border-rule px-3 py-1 md:hidden"
                        x-on:click="toggle()"
                        x-bind:aria-expanded="open"
                        aria-controls="menu-movil">
                    <flux:icon.menu variant="micro" />
                    Menú
                </button>
            </div>
        </div>

        {{-- Doble filete: dos líneas de 1 px con 3 px de aire (rasgo de las cartas RWS). --}}
        <div class="border-b border-ink"></div>
        <div class="mt-[3px] border-b border-hairline"></div>

        {{-- Menú móvil: en móvil el subrayado a mano no se usa, el activo lleva subrayado CSS. --}}
        <nav id="menu-movil" aria-label="Principal" class="md:hidden" x-show="open" x-collapse.duration.200ms x-cloak>
            <ul class="mx-auto flex max-w-6xl flex-col px-4 py-2">
                @foreach ($nav as $item)
                    <li class="border-b border-hairline last:border-b-0">
                        <a href="{{ $item['href'] }}"
                           wire:navigate
                           @if ($item['current']) aria-current="page" @endif
                           class="block py-3 text-graphite aria-[current=page]:text-ink aria-[current=page]:underline aria-[current=page]:decoration-2 aria-[current=page]:underline-offset-4">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </header>

    <main id="contenido" class="mx-auto w-full max-w-6xl flex-1 px-4 pb-16 pt-8 md:px-8 md:pt-10">
        {{ $slot }}
    </main>

    <x-site-footer :prototype="$prototype" />

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
