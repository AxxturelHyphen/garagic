{{-- Enlace de la cabecera. El activo lleva aria-current y data-rough-underline: rough.js le dibuja debajo el subrayado a mano. --}}
@props(['href', 'current' => false])
<span class="relative inline-flex">
    <a href="{{ $href }}"
       {{ $attributes->class('py-1 text-base text-graphite hover:text-ink aria-[current=page]:text-ink') }}
       @if ($current) aria-current="page" data-rough-underline @endif
       wire:navigate>{{ $slot }}</a>
</span>
