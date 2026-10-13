<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Security settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';



    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {

    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Password updated.'));
    }


}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    {{-- «Update password» se queda en inglés a propósito: SecurityTest lo comprueba con assertSee. --}}
    <x-pages::settings.layout :heading="__('Update password')">
        {{--
            Validación en el navegador (validatedForm, resources/js/garagic/forms.js): avisa de lo
            evidente (campo vacío, mínimo, confirmación) sin esperar al servidor. Es solo comodidad:
            Livewire sigue validando en updatePassword() y tiene la última palabra (flux:input pinta
            sus errores). x-on:submit va ANTES de wire:submit para que, si el formulario es
            inválido, submit() corte el evento y Livewire no envíe nada. El mínimo de 8 es el de
            Laravel fuera de producción; si el servidor exige más (12 en producción), lo dirá él.
        --}}
        <form method="POST" novalidate class="grid gap-6"
              x-data="validatedForm({
                  current_password: ['required'],
                  password: ['required', 'min:8'],
                  password_confirmation: ['required', 'confirmed:password'],
              })"
              x-on:submit="submit($event)"
              wire:submit="updatePassword">
            <div class="grid gap-2">
                <flux:input
                    wire:model="current_password"
                    :label="__('Contraseña actual')"
                    type="password"
                    required
                    autocomplete="current-password"
                    viewable
                    x-bind="field('current_password')"
                />
                <p id="current_password-error" class="flex items-center gap-2" x-show="hasError('current_password')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('current_password')"></span>
                </p>
            </div>

            <div class="grid gap-2">
                <flux:input
                    wire:model="password"
                    :label="__('Contraseña nueva')"
                    type="password"
                    required
                    autocomplete="new-password"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    x-bind="field('password')"
                />
                <p id="password-error" class="flex items-center gap-2" x-show="hasError('password')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('password')"></span>
                </p>
            </div>

            <div class="grid gap-2">
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Repite la contraseña')"
                    type="password"
                    required
                    autocomplete="new-password"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    x-bind="field('password_confirmation')"
                />
                <p id="password_confirmation-error" class="flex items-center gap-2" x-show="hasError('password_confirmation')" x-cloak>
                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('password_confirmation')"></span>
                </p>
            </div>

            <div>
                <flux:button variant="primary" type="submit" data-test="update-password-button">
                    {{ __('Guardar') }}
                </flux:button>
            </div>
        </form>
    </x-pages::settings.layout>

</section>
