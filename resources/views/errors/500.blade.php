{{-- Error 500: grabado real de un motor (algo se ha roto en el taller), una frase y un botón. Sin $exception ni BD. --}}
<x-layouts::public title="Error del taller (500)">
    <section class="error-page" aria-labelledby="error-title">
        <x-engraving name="engine-otto-1911" class="w-48" />

        <h1 id="error-title" class="error-title">Algo se ha roto en el taller.</h1>

        <flux:button variant="primary" :href="route('home')" wire:navigate>Volver al inicio</flux:button>
    </section>
</x-layouts::public>
