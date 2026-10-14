{{-- Total del ticket: doble filete encima (border-double pinta dos líneas de 1 px) y cifra grande en mono. --}}
@props(['label' => 'Total'])
<div class="mt-2 flex items-baseline justify-between gap-2 border-t-[3px] border-double border-ink pt-3">
    <dt>{{ $label }}</dt>
    <dd class="font-mono text-2xl tabular-nums">{{ $slot }}</dd>
</div>
