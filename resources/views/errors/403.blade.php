{{-- Error 403: la carta boca abajo, que no se puede girar (no es tuya), una frase y un botón. Sin $exception ni BD. --}}
<x-layouts::public title="Sin permiso (403)">
    <section class="error-page" aria-labelledby="error-title">
        <div class="error-card">
            <x-tarot-card :card="['numeral' => '0', 'title' => 'Garagic']" flipped :flippable="false" :tilt="false" />
        </div>

        <h1 id="error-title" class="error-title">Esta carta no es tuya.</h1>

        <flux:button variant="primary" :href="route('home')" wire:navigate>Volver al inicio</flux:button>
    </section>
</x-layouts::public>
