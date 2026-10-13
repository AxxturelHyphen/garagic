{{-- Ajustes > Apariencia (prototipo). El interruptor es el de verdad: cambia el tema al instante y Flux lo recuerda en localStorage. --}}
@php
    $tabs = [
        ['label' => 'Perfil', 'href' => route('prototype.settings.profile'), 'current' => false],
        ['label' => 'Seguridad', 'href' => route('prototype.settings.security'), 'current' => false],
        ['label' => 'Apariencia', 'href' => route('prototype.settings.appearance'), 'current' => true],
    ];
@endphp
<x-layouts::prototype title="Ajustes" :user="$user">
    @include('partials.settings-heading')

    <x-pages::settings.layout heading="Tema" :nav="$tabs">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" aria-label="Tema">
            <flux:radio value="light" icon="sun">Claro</flux:radio>
            <flux:radio value="dark" icon="moon">Oscuro</flux:radio>
            <flux:radio value="system" icon="computer-desktop">Sistema</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</x-layouts::prototype>
