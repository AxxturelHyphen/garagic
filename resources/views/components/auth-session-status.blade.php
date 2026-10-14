{{-- Mensaje de estado de la sesión (p. ej. «enlace enviado»). Sin verde: etiqueta de tinta con icono y texto. --}}
@props(['status' => null])

@if ($status)
    <div {{ $attributes->class('flex') }} role="status">
        <x-status-tag tone="calm">{{ $status }}</x-status-tag>
    </div>
@endif
