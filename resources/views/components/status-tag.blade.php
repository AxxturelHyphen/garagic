{{-- Etiqueta de urgencia sin rojo ni verde: escala de tinta (llena, filete, gris, discontinua) + icono + texto literal. strong = vencido o urgencia alta; medium = próximo; calm = al día; done = hecho; empty = sin datos o bloqueado. Las cifras del texto van en mono: los dígitos de IM Fell son «de estilo antiguo» (el 1 parece una I). --}}
@props(['tone' => 'calm', 'icon' => null])
@php
    $icon ??= match ($tone) {
        'strong' => 'triangle-alert',
        'medium' => 'clock',
        'empty' => 'circle-dashed',
        default => 'check',
    };

    // Envuelve cada número en mono sin tocar las entidades HTML (&#039; contiene dígitos).
    $text = preg_replace_callback(
        '/&#?\w+;|\d[\d.,]*\d|\d/u',
        fn (array $match): string => str_starts_with($match[0], '&') ? $match[0] : '<span class="font-mono text-[0.9em] tabular-nums">'.$match[0].'</span>',
        trim((string) $slot),
    );
@endphp
<span {{ $attributes->class(['tag', 'tag--'.$tone]) }}><flux:icon :icon="$icon" variant="micro" />{!! $text !!}</span>
