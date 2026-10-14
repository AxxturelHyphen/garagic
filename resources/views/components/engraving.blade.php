{{-- Grabado real (máscara de tinta WebP) pintado con el color del texto: sirve en claro y en oscuro. --}}
@props(['name', 'alt' => null])
@php
    // Proporción real de cada máscara (ancho/alto): reserva el hueco y evita saltos al cargar.
    $ratios = [
        'car-libbey-1900' => '900 / 592',
        'car-almond-1899' => '900 / 530',
        'moto-werner-1898' => '657 / 387',
        'van-montagu-1928' => '719 / 333',
        'engine-otto-1911' => '800 / 1077',
        'wrench-gates-1871' => '355 / 1198',
    ];
@endphp
<span {{ $attributes->class('engraving') }}
      style="--engraving: url('{{ asset('img/engravings/'.$name.'.webp') }}'); aspect-ratio: {{ $ratios[$name] ?? '4 / 3' }}"
      @if ($alt) role="img" aria-label="{{ $alt }}" @else aria-hidden="true" @endif></span>
