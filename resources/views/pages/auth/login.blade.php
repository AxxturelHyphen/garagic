<x-layouts::auth title="Entrar" art="carro">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Entrar" />

        <x-auth-session-status :status="session('status')" />

        {{-- Cliente: required + email. Servidor (Fortify): comprueba las credenciales y tiene la última palabra. --}}
        <x-auth-form :action="route('login.store')" :rules="['email' => ['required', 'email'], 'password' => ['required']]">
            <flux:field>
                <flux:label>Correo</flux:label>
                <flux:input name="email" type="email" :value="old('email')" autofocus autocomplete="email" x-bind="field('email')" />
                <x-auth-error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Contraseña</flux:label>
                <flux:input name="password" type="password" autocomplete="current-password" viewable x-bind="field('password')" />
                <x-auth-error name="password" />

                @if (Route::has('password.request'))
                    <flux:link class="text-sm" :href="route('password.request')" wire:navigate>¿Olvidaste la contraseña?</flux:link>
                @endif
            </flux:field>

            <flux:checkbox name="remember" label="Recordarme" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">Entrar</flux:button>
        </x-auth-form>

        <p class="text-graphite">
            ¿No tienes cuenta?
            <flux:link :href="route('register')" wire:navigate>Crear cuenta</flux:link>
        </p>
    </div>
</x-layouts::auth>
