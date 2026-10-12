{{--
    Marco de las páginas públicas (portada, errores): misma cabecera de libreta y mismo pie
    que la app, pero con acceso (Entrar / Crear cuenta) en vez de menú de usuario.
--}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    @include('partials.head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-card focus:px-3 focus:py-2">Saltar al contenido</a>

    <header>
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 md:px-8">
            <x-app-logo :href="route('home')" wire:navigate />

            <div class="flex items-center gap-4">
                @auth
                    <flux:button variant="primary" :href="route('dashboard')" wire:navigate>Ir a mi baraja</flux:button>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" wire:navigate class="text-graphite hover:text-ink">Entrar</a>
                    @endif

                    @if (Route::has('register'))
                        <flux:button variant="primary" :href="route('register')" wire:navigate>Crear cuenta</flux:button>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Doble filete: dos líneas de 1 px con 3 px de aire (rasgo de las cartas RWS). --}}
        <div class="border-b border-ink"></div>
        <div class="mt-[3px] border-b border-hairline"></div>
    </header>

    <main id="contenido" class="mx-auto w-full max-w-6xl flex-1 px-4 pb-16 pt-8 md:px-8 md:pt-10">
        {{ $slot }}
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
