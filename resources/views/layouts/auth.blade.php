{{-- Marco de acceso: las pantallas de pages/auth lo usan como <x-layouts::auth>. Reenvía a layouts/auth/simple. --}}
@props(['title' => null, 'art' => 'carro'])
<x-layouts::auth.simple :title="$title" :art="$art">
    {{ $slot }}
</x-layouts::auth.simple>
