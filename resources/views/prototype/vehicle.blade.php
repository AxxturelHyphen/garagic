@php($v = $vehicles[(int) $vehicle])
<x-layouts::prototype :title="$v['name']" :user="$user">
    <x-page-header :title="$v['name']" :numeral="$v['numeral']" :back="['href' => route('prototype.deck'), 'label' => 'Baraja']" />

    <p class="text-graphite">En construcción.</p>
</x-layouts::prototype>
