<?php

use Livewire\Component;

new class extends Component {}; ?>

{{-- Zona de borrado: separada de los formularios y con el verbo explícito en el botón. --}}
<x-panel :title="__('Borrar cuenta')" class="mt-10 max-w-xl">
    <div class="grid justify-items-start gap-4">
        <p>{{ __('Se borran tu cuenta, tus vehículos y su historial. No se puede deshacer.') }}</p>

        <flux:modal.trigger name="confirm-user-deletion">
            <flux:button variant="danger" data-test="delete-user-button">
                {{ __('Borrar mi cuenta') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <livewire:pages::settings.delete-user-modal />
</x-panel>
