<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="w-full max-w-lg">
    <form method="POST" wire:submit="deleteUser" class="grid gap-6">
        <div class="grid gap-2">
            <flux:heading size="lg" class="pe-8">{{ __('¿Seguro que quieres borrar tu cuenta?') }}</flux:heading>
            <p class="text-graphite">{{ __('Escribe tu contraseña para confirmar.') }}</p>
        </div>

        <flux:input wire:model="password" :label="__('Contraseña')" type="password" viewable />

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="outline">{{ __('Cancelar') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                {{ __('Borrar mi cuenta') }}
            </flux:button>
        </div>
    </form>
</flux:modal>
