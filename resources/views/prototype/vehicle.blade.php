{{--
    Ficha del vehículo: la carta grande (gira y se inclina), su vitalidad, sus avisos, el gasto en un
    ticket, los últimos rituales y los sellos ganados. Datos de _fake-data.php, elegidos por id.
--}}
@php
    $v = $vehicles[(int) $vehicle];

    // Presentación, no lógica: lo vencido primero, luego lo próximo y al final lo que está al día.
    // sortBy es estable, así que dentro de cada estado se conserva el orden de llegada.
    $statusOrder = ['overdue' => 0, 'soon' => 1, 'ok' => 2];
    $vehicleWarnings = collect($warnings)
        ->where('vehicle_id', $v['id'])
        ->whereIn('status', array_keys($statusOrder))
        ->sortBy(fn (array $w): int => $statusOrder[$w['status']]);

    $lastRituals = collect($rituals)->where('vehicle_id', $v['id'])->sortByDesc('date')->take(3);

    // Un sello ganado con este vehículo se estampa también sobre la carta.
    $stamps = collect($achievements)->filter(fn (array $a): bool => $a['unlocked'] && $a['earned_with'] === $v['id'])->values();
    $stamp = $stamps->first();

    $isEmpty = $v['vitality'] === null;
@endphp
<x-layouts::prototype :title="$v['name']" :user="$user">
    <x-page-header :title="$v['name']" :numeral="$v['numeral']" :back="['href' => route('prototype.deck'), 'label' => 'Baraja']">
        <x-slot:actions>
            <flux:button variant="primary" icon="plus" :href="route('prototype.rituals')" wire:navigate>Registrar ritual</flux:button>
            <flux:button variant="outline" icon="pencil" :href="route('prototype.vehicles.edit', $v['id'])" wire:navigate>Editar</flux:button>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-10 lg:grid-cols-[20rem_minmax(0,1fr)]">
        {{-- x-data vacío: el botón de fuera necesita un ámbito de Alpine para avisar a la carta (evento tarot-flip). --}}
        <div class="garage-sticky mx-auto grid w-72 justify-items-center gap-5 sm:w-80 lg:mx-0" x-data>
            <x-tarot-card size="fluid" :name="'vehicle-'.$v['id']" :card="$v['card']" :vitality="$v['vitality'] ?? 100" :empty="$isEmpty">
                @if ($stamp)
                    <x-slot:stamp>
                        <x-achievement-stamp size="mark" :name="$stamp['name']" :emblem="$emblems[$stamp['key']] ?? null" :icon="$stamp['icon']" :tilt="$stamp['tilt']" :fresh="$stamp['fresh']" />
                    </x-slot:stamp>
                @endif
            </x-tarot-card>

            <flux:button variant="outline" x-on:click="$dispatch('tarot-flip', 'vehicle-{{ $v['id'] }}')">Girar carta</flux:button>
        </div>

        <div class="grid min-w-0 content-start gap-10">
            <x-vitality-bar :value="$v['vitality']" size="lg" />

            <section class="garage-rows" aria-labelledby="avisos">
                <h2 id="avisos" class="mb-3 font-display text-2xl">Avisos</h2>

                @if ($isEmpty)
                    <ul role="list" class="border-t border-hairline">
                        <x-ritual-row status="none" task="Sin rituales registrados" />
                    </ul>
                @else
                    <ul role="list" class="border-t border-hairline">
                        @foreach ($vehicleWarnings as $w)
                            <x-ritual-row :status="$w['status']"
                                          :task="$taskTypes[$w['task']]['name']"
                                          :icon="$taskTypes[$w['task']]['icon']"
                                          :label="$w['label']"
                                          :date="$w['due_date']"
                                          :km="$w['due_km']" />
                        @endforeach
                    </ul>
                @endif
            </section>

            @unless ($isEmpty)
                <div class="grid items-start gap-10 sm:grid-cols-[minmax(0,20rem)_minmax(0,1fr)]">
                    <x-ticket title="Gasto">
                        <x-ticket.row label="Este año"><x-money :amount="$v['spent_year']" /></x-ticket.row>
                        <x-ticket.row label="Por km"><span>{{ $v['cost_per_km'] }}</span></x-ticket.row>
                        <x-ticket.row label="Rituales"><span>{{ $v['rituals_count'] }}</span></x-ticket.row>
                        <x-ticket.total label="Total"><x-money :amount="$v['spent_total']" /></x-ticket.total>
                    </x-ticket>

                    @if ($stamps->isNotEmpty())
                        <section aria-labelledby="sellos">
                            <h2 id="sellos" class="mb-4 font-display text-2xl">Sellos</h2>
                            <div class="flex flex-wrap items-start gap-6">
                                @foreach ($stamps as $achievement)
                                    <x-achievement-stamp size="sm" :name="$achievement['name']" :emblem="$emblems[$achievement['key']] ?? null"
                                                         :icon="$achievement['icon']" :date="$achievement['date']" :tilt="$achievement['tilt']" />
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <section class="garage-rows" aria-labelledby="ultimos">
                    <div class="mb-3 flex flex-wrap items-baseline justify-between gap-3">
                        <h2 id="ultimos" class="font-display text-2xl">Últimos rituales</h2>
                        <a href="{{ route('prototype.vehicles.history', $v['id']) }}" wire:navigate class="text-ink underline">Historial</a>
                    </div>

                    <ul role="list" class="border-t border-hairline">
                        @foreach ($lastRituals as $ritual)
                            <x-ritual-row status="done"
                                          :task="$taskTypes[$ritual['task']]['name']"
                                          :icon="$taskTypes[$ritual['task']]['icon']"
                                          :date="$ritual['date']"
                                          :cost="$ritual['cost']" />
                        @endforeach
                    </ul>
                </section>
            @endunless
        </div>
    </div>
</x-layouts::prototype>
