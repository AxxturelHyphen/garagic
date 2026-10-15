{{--
    Marco de la app real (usuario autenticado): le pasa los datos de la sesión al shell.
    El enlace de administración depende de la Gate 'admin', no del rol a mano,
    para que la regla de quién es admin viva en un solo sitio.
--}}
@php
    $authUser = auth()->user();

    $nav = array_values(array_filter([
        ['label' => 'Baraja', 'href' => route('dashboard'), 'current' => request()->routeIs('dashboard')],
        $authUser->can('admin')
            ? ['label' => 'Administración', 'href' => route('admin.index'), 'current' => request()->routeIs('admin.*')]
            : null,
    ]));
@endphp
<x-app-shell
    :title="$title ?? null"
    :user="['name' => $authUser->name, 'email' => $authUser->email, 'initials' => $authUser->initials(), 'role' => $authUser->role->value]"
    :nav="$nav"
    :settings-href="route('profile.edit')"
    :logout-action="route('logout')">
    {{ $slot }}
</x-app-shell>
