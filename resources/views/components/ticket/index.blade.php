{{-- Ticket: bloque de cifras con aspecto de recibo (bordes de troquel arriba y abajo). Uso: <x-ticket title="Gasto"><x-ticket.row label="En 2026"><x-money :amount="683.2" /></x-ticket.row><x-ticket.total label="Total">…</x-ticket.total></x-ticket> --}}
@props(['title'])
<section {{ $attributes->class('ticket') }} aria-label="{{ $title }}">
    <h2 class="font-display text-2xl">{{ $title }}</h2>
    <dl class="mt-3 grid gap-2">{{ $slot }}</dl>
</section>
