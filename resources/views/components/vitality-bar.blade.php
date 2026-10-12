{{-- Vitalidad de un vehículo: número, texto de estado y barra. El estado nunca va solo en la barra: se lee en texto y se distingue por el relleno (llena, rayada, rayado suelto) sin usar color. Sin valor: «Sin datos» y qué hacer. --}}
@props(['value' => null, 'size' => 'md', 'href' => null])
@php
    $band = match (true) {
        $value === null => 'none',
        $value >= 80 => 'good',
        $value >= 50 => 'mid',
        default => 'low',
    };
    // Umbrales SOLO de presentación (texto y rayado). El cálculo real de la vitalidad irá en una clase PHP propia.
    $state = ['good' => 'Bien cuidado', 'mid' => 'Necesita atención', 'low' => 'Descuidado', 'none' => 'Sin datos'][$band];
@endphp
<div {{ $attributes->class(['vitality', 'vitality--'.$size]) }} data-band="{{ $band }}" style="--vitality: {{ $value ?? 0 }}">
    <div class="flex flex-wrap items-baseline gap-x-3">
        <span class="text-graphite">Vitalidad</span>
        @if ($value === null)
            <span class="font-mono leading-none {{ $size === 'lg' ? 'text-4xl' : 'text-2xl' }}" aria-hidden="true">--</span>
        @else
            <span class="font-display leading-none {{ $size === 'lg' ? 'text-5xl' : 'text-3xl' }}">{{ $value }}</span>
        @endif
        <span>{{ $state }}</span>
    </div>

    @if ($value === null)
        <div class="vitality__track" aria-hidden="true"></div>
        <p class="mt-2 text-graphite">
            Registra un ritual para calcularla.
            @if ($href)
                <a href="{{ $href }}" class="text-ink underline">Registrar ritual</a>
            @endif
        </p>
    @else
        <div class="vitality__track" role="meter" aria-label="Vitalidad"
             aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $value }}"
             aria-valuetext="{{ $value }} de 100: {{ mb_strtolower($state) }}">
            <div class="vitality__fill"></div>
        </div>
    @endif
</div>
