{{--
    Ajustes > Perfil (prototipo, sin sesión). Misma pantalla que pages/settings/profile (SFC), pero con el
    usuario falso y sin Livewire: guardar solo enseña el aviso y el modal de borrar solo se cierra.
--}}
@php
    $tabs = [
        ['label' => 'Perfil', 'href' => route('prototype.settings.profile'), 'current' => true],
        ['label' => 'Seguridad', 'href' => route('prototype.settings.security'), 'current' => false],
        ['label' => 'Apariencia', 'href' => route('prototype.settings.appearance'), 'current' => false],
    ];
@endphp
<x-layouts::prototype title="Ajustes" :user="$user">
    @include('partials.settings-heading')

    <x-pages::settings.layout heading="Tus datos" :nav="$tabs">
        <form class="grid gap-6" x-data
              x-on:submit.prevent="$dispatch('toast-show', { duration: 4000, slots: { text: 'Perfil guardado.' }, dataset: {} })">
            <flux:input name="name" label="Nombre" type="text" :value="$user['name']" autocomplete="name" />
            <flux:input name="email" label="Correo" type="email" :value="$user['email']" autocomplete="email" />

            <div>
                <flux:button variant="primary" type="submit" :loading="false">Guardar</flux:button>
            </div>
        </form>
    </x-pages::settings.layout>

    <x-panel title="Borrar cuenta" class="mt-10 max-w-xl">
        <div class="grid justify-items-start gap-4">
            <p>Se borran tu cuenta, tus vehículos y su historial. No se puede deshacer.</p>

            <flux:modal.trigger name="confirm-user-deletion">
                <flux:button variant="danger">Borrar mi cuenta</flux:button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="confirm-user-deletion" focusable class="w-full max-w-lg">
            <form class="grid gap-6" x-data x-on:submit.prevent>
                <div class="grid gap-2">
                    <flux:heading size="lg" class="pe-8">¿Seguro que quieres borrar tu cuenta?</flux:heading>
                    <p class="text-graphite">Escribe tu contraseña para confirmar.</p>
                </div>

                <flux:input name="password" label="Contraseña" type="password" viewable />

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="outline">Cancelar</flux:button>
                    </flux:modal.close>

                    <flux:modal.close>
                        <flux:button variant="danger">Borrar mi cuenta</flux:button>
                    </flux:modal.close>
                </div>
            </form>
        </flux:modal>
    </x-panel>
</x-layouts::prototype>
