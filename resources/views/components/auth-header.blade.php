{{-- Título de las pantallas de acceso: solo el h1 caligráfico, alineado a la izquierda. `description` es opcional y solo se usa si la pantalla no tiene campos que se expliquen solos (verificar correo). --}}
@props(['title', 'description' => null])

<div class="flex w-full flex-col gap-2">
    <h1 class="font-display text-3xl tracking-display md:text-4xl">{{ $title }}</h1>

    @if ($description)
        <p class="text-graphite">{{ $description }}</p>
    @endif
</div>
