<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    {{-- Flux guarda la elección en localStorage ($flux.appearance) y cambia la clase `dark` al instante: no hay petición. --}}
    <x-pages::settings.layout :heading="__('Tema')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" aria-label="{{ __('Tema') }}">
            <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Oscuro') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>
