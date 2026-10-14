<x-layouts::auth title="Confirmar correo" art="estrella">
    <div class="flex flex-col gap-6">
        {{-- Esta pantalla no tiene campos que se expliquen solos: es la única con una frase. --}}
        <x-auth-header title="Confirma tu correo" description="Abre el enlace que te hemos enviado." />

        @if (session('status') == 'verification-link-sent')
            <x-auth-session-status status="Te hemos enviado un enlace nuevo." />
        @endif

        <div class="flex flex-wrap items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary">Reenviar</flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="cursor-pointer" data-test="logout-button">Cerrar sesión</flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
