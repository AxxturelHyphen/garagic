<x-layouts::auth title="Contraseña nueva" art="rueda">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Contraseña nueva" />

        <x-auth-form :action="route('password.update')" :rules="[
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'confirmed:password'],
        ]">
            {{-- El token del enlace del correo: sin él, Fortify no deja cambiar la contraseña. --}}
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <flux:field>
                <flux:label>Correo</flux:label>
                <flux:input name="email" type="email" value="{{ request('email') }}" autocomplete="email" x-bind="field('email')" />
                <x-auth-error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Contraseña nueva</flux:label>
                <flux:input
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    x-bind="field('password')"
                />
                <x-auth-error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Repite la contraseña</flux:label>
                <flux:input
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    x-bind="field('password_confirmation')"
                />
                <x-auth-error name="password_confirmation" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">Guardar contraseña</flux:button>
        </x-auth-form>
    </div>
</x-layouts::auth>
