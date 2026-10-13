{{-- Variante del starter que Garagic no usa: hay un único marco de acceso (layouts/auth/simple). Se deja como alias para no romper a quien la llame. --}}
@props(['title' => null, 'art' => 'carro'])
<x-layouts::auth.simple :title="$title" :art="$art">
    {{ $slot }}
</x-layouts::auth.simple>
