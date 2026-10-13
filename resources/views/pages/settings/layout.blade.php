{{--
    Marco común de las pestañas de ajustes: navegación (Perfil, Seguridad, Apariencia) y un
    panel de cartulina con el título de la pestaña y su contenido. Lo usan los SFC reales y
    los mocks del prototipo; estos últimos pasan sus propias pestañas con :nav
    (lista de ['label', 'href', 'current']). Con nav = null se usan las rutas reales.
--}}
@props(['heading' => '', 'subheading' => '', 'nav' => null])
@php
    $tabs = $nav ?? [
        ['label' => __('Perfil'), 'href' => route('profile.edit'), 'current' => request()->routeIs('profile.edit')],
        ['label' => __('Seguridad'), 'href' => route('security.edit'), 'current' => request()->routeIs('security.edit')],
        ['label' => __('Apariencia'), 'href' => route('appearance.edit'), 'current' => request()->routeIs('appearance.edit')],
    ];
@endphp
<div>
    {{-- Pestañas como enlaces: cada una es una página y una URL, así que no necesitan JS ni estado. --}}
    <nav aria-label="{{ __('Ajustes') }}" class="flex gap-8 overflow-x-auto border-b border-hairline pb-2">
        @foreach ($tabs as $tab)
            <x-nav-link :href="$tab['href']" :current="$tab['current']">{{ $tab['label'] }}</x-nav-link>
        @endforeach
    </nav>

    <x-panel variant="raised" :title="$heading" class="mt-8 max-w-xl">
        @if (filled($subheading))
            <p class="-mt-2 mb-4 text-graphite">{{ $subheading }}</p>
        @endif

        {{ $slot }}
    </x-panel>
</div>
