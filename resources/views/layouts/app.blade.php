{{--
    Marco de la app real (usuario autenticado): le pasa los datos de la sesión al shell.
    El rol aún no existe en la tabla de usuarios; cuando exista, irá en 'role'.
--}}
@php($authUser = auth()->user())
<x-app-shell
    :title="$title ?? null"
    :user="['name' => $authUser->name, 'email' => $authUser->email, 'initials' => $authUser->initials(), 'role' => null]"
    :nav="[['label' => 'Baraja', 'href' => route('dashboard'), 'current' => request()->routeIs('dashboard')]]"
    :settings-href="route('profile.edit')"
    :logout-action="route('logout')">
    {{ $slot }}
</x-app-shell>
