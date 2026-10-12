{{-- Pie de página: doble filete (rasgo del tarot RWS), marca pequeña y selector de apariencia. En el prototipo añade los enlaces al mapa y a la guía de estilo. --}}
@props(['prototype' => false])
<footer class="mt-16">
    <div class="border-t border-ink"></div>
    <div class="mt-[3px] border-t border-hairline"></div>

    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-6 text-graphite md:px-8">
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
            <span class="inline-flex items-center gap-2 text-ink">
                <x-app-logo-icon class="size-6" />
                <span class="font-display text-xl leading-none">Garagic</span>
            </span>

            @if ($prototype)
                <a href="{{ route('prototype.index') }}" class="hover:text-ink">Mapa</a>
                <a href="{{ route('prototype.style') }}" class="hover:text-ink">Estilo</a>
            @endif
        </div>

        <x-appearance-switch />
    </div>
</footer>
