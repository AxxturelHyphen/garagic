<x-layouts::auth title="Crear cuenta" art="estrella">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Crear cuenta" />

        {{-- Aquí NO se elige rol: dejar que cualquiera se hiciera admin sería una escalada de privilegios.
             El rol admin se asigna fuera del formulario (seeder). --}}
        <x-auth-form :action="route('register.store')" :rules="[
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'confirmed:password'],
        ]">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input name="name" type="text" :value="old('name')" autofocus autocomplete="name" x-bind="field('name')" />
                <x-auth-error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Correo</flux:label>
                <flux:input name="email" type="email" :value="old('email')" autocomplete="email" x-bind="field('email')" />
                <x-auth-error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Contraseña</flux:label>
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

            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">Crear cuenta</flux:button>
        </x-auth-form>

        <p class="text-graphite">
            ¿Ya tienes cuenta?
            <flux:link :href="route('login')" wire:navigate>Entrar</flux:link>
        </p>
    </div>
</x-layouts::auth>
