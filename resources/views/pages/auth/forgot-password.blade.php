<x-layouts::auth title="Recuperar contraseña" art="rueda">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Recuperar contraseña" />

        {{-- Fortify responde con su frase en inglés: se sustituye por la nuestra. Es el único estado que esta pantalla recibe. --}}
        <x-auth-session-status :status="session('status') ? 'Si el correo está registrado, te llegará el enlace.' : null" />

        <x-auth-form :action="route('password.email')" :rules="['email' => ['required', 'email']]">
            <flux:field>
                <flux:label>Correo</flux:label>
                <flux:input name="email" type="email" :value="old('email')" autofocus autocomplete="email" x-bind="field('email')" />
                <x-auth-error name="email" />
            </flux:field>

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">Enviar enlace</flux:button>
        </x-auth-form>

        <p>
            <flux:link :href="route('login')" wire:navigate>Volver a entrar</flux:link>
        </p>
    </div>
</x-layouts::auth>
