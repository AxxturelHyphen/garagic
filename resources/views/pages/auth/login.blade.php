<x-layouts::auth :title="__('Login')">
    <div class="flex flex-col gap-6">
        
        <x-auth-header :title="__('Log in to your shi')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />
        {{-- Acordeón de prueba en Alpine (equivalente gratis a flux:accordion, que es Pro).
             `abierto` guarda el id del ítem abierto (null = ninguno): así solo hay uno abierto a la vez.
             Todo ocurre en el navegador, sin petición al servidor. --}}
        <div x-data="{ abierto: null }" class="divide-y divide-zinc-200 dark:divide-zinc-700 border-y border-zinc-200 dark:border-zinc-700">
            @foreach ([
                1 => ['¿Cuál es la política de reembolso?', 'Si no estás satisfecho, ofrecemos 30 días de garantía.'],
                2 => ['¿Hay descuentos por volumen?', 'Sí, contacta con ventas con tus necesidades.'],
                3 => ['¿Cómo sigo mi pedido?', 'Recibirás un email con un número de seguimiento.'],
            ] as $id => [$titulo, $texto])
                <div>
                    {{-- Si ya está abierto lo cierra (null); si no, abre este. --}}
                    <button type="button"
                            @click="abierto = abierto === {{ $id }} ? null : {{ $id }}"
                            :aria-expanded="abierto === {{ $id }}"
                            class="flex w-full items-center justify-between py-3 text-start text-sm font-medium">
                        {{ $titulo }}
                        <span class="transition-transform duration-200 motion-reduce:transition-none"
                              :class="abierto === {{ $id }} && 'rotate-180'">▾</span>
                    </button>
                    {{-- x-collapse (plugin de Alpine incluido en Livewire) anima la altura. --}}
                    <div x-show="abierto === {{ $id }}" x-collapse x-cloak class="pb-3 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ $texto }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
