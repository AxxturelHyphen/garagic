@php($v = $vehicles[(int) $vehicle])
<x-layouts::prototype title="Historial" :user="$user">
    <x-page-header title="Historial" :back="['href' => route('prototype.vehicles.show', $vehicle), 'label' => $v['name']]" />

    <p class="text-graphite">En construcción.</p>
</x-layouts::prototype>
