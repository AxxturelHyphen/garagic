{{-- Error 404: una carta boca abajo y del revés («carta invertida»), una frase y un botón. Sin $exception ni BD. --}}
<x-layouts::public title="No encontrada (404)">
    <section class="error-page" aria-labelledby="error-title">
        <div class="error-card rotate-180">
            <x-tarot-card :card="['numeral' => '0', 'title' => 'Garagic']" flipped :flippable="false" :tilt="false" />
        </div>

        <h1 id="error-title" class="error-title">Esta carta no está en la baraja.</h1>

        <flux:button variant="primary" :href="route('home')" wire:navigate>Volver al inicio</flux:button>
    </section>
</x-layouts::public>
