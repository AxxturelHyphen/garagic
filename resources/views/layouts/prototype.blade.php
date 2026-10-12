{{--
    Marco del prototipo visual: el mismo shell que la app real, pero con un usuario falso
    y enlaces a las pantallas /prototipo/*, para ver todo sin login ni base de datos.
--}}
@props(['title' => null, 'user' => null])
@php
    // Usuario falso: las vistas lo pasan; si no (el Oráculo, que es Livewire), se lee de los datos falsos.
    $user ??= (require resource_path('views/prototype/_fake-data.php'))['user'];
    $isAdmin = ($user['role'] ?? null) === 'admin';

    $nav = array_values(array_filter([
        ['label' => 'Baraja', 'href' => route('prototype.deck'), 'current' => request()->routeIs('prototype.deck', 'prototype.vehicles.*')],
        ['label' => 'Rituales', 'href' => route('prototype.rituals'), 'current' => request()->routeIs('prototype.rituals')],
        ['label' => 'Oráculo', 'href' => route('prototype.oracle'), 'current' => request()->routeIs('prototype.oracle')],
        ['label' => 'Logros', 'href' => route('prototype.achievements'), 'current' => request()->routeIs('prototype.achievements')],
        // El catálogo es del admin: el usuario normal no lo ve.
        $isAdmin ? ['label' => 'Catálogo', 'href' => route('prototype.admin'), 'current' => request()->routeIs('prototype.admin')] : null,
    ]));
@endphp
<x-app-shell
    :title="$title"
    :user="$user"
    :nav="$nav"
    :settings-href="route('prototype.settings.profile')"
    :logout-href="route('prototype.auth.login')"
    prototype>
    {{ $slot }}
</x-app-shell>
