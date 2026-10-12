@php($title = $mode === 'edit' ? 'Editar vehículo' : 'Nuevo vehículo')
<x-layouts::prototype :title="$title" :user="$user">
    <x-page-header :title="$title" :back="['href' => route('prototype.deck'), 'label' => 'Baraja']" />

    <p class="text-graphite">En construcción.</p>
</x-layouts::prototype>
