{{-- Menú de usuario: sello cuadrado con las iniciales que abre un menú de Flux (nombre, ajustes y salir). En la app real "Salir" es un POST (logoutAction); en el prototipo, un enlace (logoutHref). --}}
@props(['user', 'settingsHref' => null, 'logoutAction' => null, 'logoutHref' => null])
<flux:dropdown position="bottom" align="end">
    <button type="button"
            class="grid size-9 cursor-pointer place-items-center border border-ink font-mono text-sm"
            aria-label="Menú de {{ $user['name'] }}"
            data-test="sidebar-menu-button">{{ $user['initials'] }}</button>

    <flux:menu>
        <div class="px-2 py-1.5">
            <p class="truncate">{{ $user['name'] }}</p>
            <p class="truncate text-sm text-graphite">{{ $user['email'] }}</p>
            @if (($user['role'] ?? null) === 'admin')
                <p class="mt-1 inline-block border border-ink px-2 text-sm">Admin</p>
            @endif
        </div>

        <flux:menu.separator />

        @if ($settingsHref)
            <flux:menu.item icon="settings" :href="$settingsHref" wire:navigate>Ajustes</flux:menu.item>
        @endif

        @if ($logoutAction)
            <form method="POST" action="{{ $logoutAction }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="log-out" class="w-full cursor-pointer" data-test="logout-button">
                    Cerrar sesión
                </flux:menu.item>
            </form>
        @elseif ($logoutHref)
            <flux:menu.item icon="log-out" :href="$logoutHref" data-test="logout-button">Cerrar sesión</flux:menu.item>
        @endif
    </flux:menu>
</flux:dropdown>
