{{--
    Ajustes > Seguridad (prototipo, sin sesión). Usa el mismo validatedForm que el SFC real
    (SFC pages/settings/security): un envío inválido se corta en el navegador; uno válido solo enseña el aviso.
--}}
@php
    $tabs = [
        ['label' => 'Perfil', 'href' => route('prototype.settings.profile'), 'current' => false],
        ['label' => 'Seguridad', 'href' => route('prototype.settings.security'), 'current' => true],
        ['label' => 'Apariencia', 'href' => route('prototype.settings.appearance'), 'current' => false],
    ];
@endphp
<x-layouts::prototype title="Ajustes" :user="$user">
    @include('partials.settings-heading')

    <x-pages::settings.layout heading="Cambiar contraseña" :nav="$tabs">
        {{-- submit() corta el evento si hay errores (defaultPrevented); si no, el prototipo lo para a mano y avisa. --}}
        <form novalidate class="grid gap-6"
              x-data="validatedForm({
                  current_password: ['required'],
                  password: ['required', 'min:8'],
                  password_confirmation: ['required', 'confirmed:password'],
              })"
              x-on:submit="submit($event); if (! $event.defaultPrevented) { $event.preventDefault(); $dispatch('toast-show', { duration: 4000, slots: { text: 'Contraseña guardada.' }, dataset: {} }) }">
            <div class="grid gap-2">
                <flux:input name="current_password" label="Contraseña actual" type="password" required
                            autocomplete="current-password" viewable x-bind="field('current_password')" />
                <p id="current_password-error" class="flex items-center gap-2" x-show="hasError('current_password')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('current_password')"></span>
                </p>
            </div>

            <div class="grid gap-2">
                <flux:input name="password" label="Contraseña nueva" type="password" required
                            autocomplete="new-password" viewable x-bind="field('password')" />
                <p id="password-error" class="flex items-center gap-2" x-show="hasError('password')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('password')"></span>
                </p>
            </div>

            <div class="grid gap-2">
                <flux:input name="password_confirmation" label="Repite la contraseña" type="password" required
                            autocomplete="new-password" viewable x-bind="field('password_confirmation')" />
                <p id="password_confirmation-error" class="flex items-center gap-2" x-show="hasError('password_confirmation')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('password_confirmation')"></span>
                </p>
            </div>

            <div>
                <flux:button variant="primary" type="submit" :loading="false">Guardar</flux:button>
            </div>
        </form>
    </x-pages::settings.layout>
</x-layouts::prototype>
