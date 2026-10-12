{{-- Fila de aviso o de ritual hecho, escrita sobre el papel (sin caja). El estado se dice con texto literal (label), icono y, si está vencido, rayado a la izquierda. Va dentro de <ul role="list" class="border-t border-hairline">. status: overdue | soon | ok | done | none. --}}
@props([
    'task',
    'icon' => 'wrench',
    'status' => 'ok',
    'label' => null,
    'vehicle' => null,
    'date' => null,
    'km' => null,
    'cost' => null,
    'href' => null,
])
@php
    $tone = ['overdue' => 'strong', 'soon' => 'medium', 'ok' => 'calm', 'done' => 'done', 'none' => 'empty'][$status];
@endphp
<li {{ $attributes->class('ritual-row') }} data-status="{{ $status }}">
    <flux:icon :icon="$status === 'none' ? 'circle-dashed' : $icon" class="ritual-row__icon size-5 text-graphite" />

    <div class="ritual-row__task min-w-0">
        <p>
            @if ($href)
                <a href="{{ $href }}" class="text-ink no-underline hover:underline">{{ $task }}</a>
            @else
                {{ $task }}
            @endif
        </p>
        @if ($vehicle)
            <p class="text-graphite">{{ $vehicle }}</p>
        @endif
    </div>

    <div class="ritual-row__due text-sm">
        @if ($date)
            <x-date :value="$date" />
        @elseif ($km)
            <x-km :value="$km" />
        @endif
    </div>

    <div class="ritual-row__tag">
        @if ($status !== 'done' && $label)
            <x-status-tag :tone="$tone">{{ $label }}</x-status-tag>
        @endif
    </div>

    <div class="ritual-row__cost text-end">
        @if ($cost !== null)
            <x-money :amount="$cost" />
        @endif
    </div>
</li>
