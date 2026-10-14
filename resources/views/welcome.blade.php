{{--
    Portada. El hero ES la carta (El Carro, arcano VII del tarot Rider-Waite-Smith de 1909): llega
    boca abajo, se posa y se gira (reparto de 700 ms), se inclina con el cursor y gira al pulsarla.
    Texto mínimo a propósito: un titular, una frase y dos botones. El resto lo cuenta la carta.
    Los estilos están en resources/css/screens/public.css.
--}}
@php
    // Es ilustración, no datos de usuario: una carta de muestra con su dorso (lo que verías de un vehículo).
    $showcase = [
        'numeral' => 'VII',
        'title' => 'El Carro',
        'plate' => '0000 GRG',
        // Zona buena del escaneo (píxeles izq., arriba, der., abajo): sin margen ni numeral ni título en inglés.
        'arcana' => ['src' => '/img/arcana/07-el-carro.webp', 'w' => 520, 'h' => 898, 'crop' => [28, 62, 488, 776]],
        'back' => [
            'Kilómetros' => '68.420 km',
            'Próxima ITV' => '14/05/2027',
            'Seguro hasta' => '01/03/2027',
            'Aceite' => 'en 6.580 km',
        ],
    ];
@endphp
<x-layouts::public>
    <section class="home-hero" aria-labelledby="home-title">
        <div class="grid max-w-xl gap-6">
            <h1 id="home-title" class="home-title tracking-normal">Tu garaje, en una baraja.</h1>

            <p class="max-w-[38ch] text-xl text-graphite">
                Garagic te avisa de la ITV, el seguro, el aceite y las ruedas, y apunta lo que te cuesta cada vehículo.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                @auth
                    <flux:button variant="primary" :href="route('dashboard')" wire:navigate>Ir a mi baraja</flux:button>
                @else
                    @if (Route::has('register'))
                        <flux:button variant="primary" :href="route('register')" wire:navigate>Crear cuenta</flux:button>
                    @endif
                    @if (Route::has('login'))
                        <flux:button variant="outline" :href="route('login')" wire:navigate>Entrar</flux:button>
                    @endif
                @endauth
            </div>
        </div>

        <div class="home-card">
            <x-tarot-card :card="$showcase" :vitality="95" size="fluid" entrance />
        </div>
    </section>
</x-layouts::public>
