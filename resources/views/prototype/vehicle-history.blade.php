{{--
    Historial y gasto de un vehículo: libro mayor (tabla a mano, .ledger) y totales en ticket.
    Se puede imprimir para enseñárselo a quien compre el vehículo (CSS de impresión en screens/rituals.css).
    Los totales se suman aquí desde los rituales; en la app real los dará una consulta (SUM) a la base de datos.
    Datos de resources/views/prototype/_fake-data.php.
--}}
@php
    $v = $vehicles[(int) $vehicle];

    // Más reciente primero: es lo que se quiere ver al abrir el historial.
    $entries = collect($rituals)->where('vehicle_id', $v['id'])->sortByDesc('date')->values();

    $total = $entries->sum('cost');
    $year = \Illuminate\Support\Carbon::parse($today)->year;
    $thisYear = $entries->filter(fn (array $r): bool => \Illuminate\Support\Carbon::parse($r['date'])->year === $year)->sum('cost');

    // Gasto por tipo de tarea, el mayor primero.
    $byType = $entries->groupBy('task')->map(fn ($group) => $group->sum('cost'))->sortDesc();
@endphp
<x-layouts::prototype title="Historial y gasto" :user="$user">
    <x-page-header title="Historial y gasto" :back="['href' => route('prototype.vehicles.show', $vehicle), 'label' => $v['name']]" class="print:hidden">
        <x-slot:actions>
            {{-- Cambiar de vehículo es navegar a otra URL: no hace falta Livewire ni Alpine más que para el evento. --}}
            <flux:select class="w-full sm:w-56 print:hidden" aria-label="Vehículo" x-data x-on:change="window.location = $event.target.value">
                @foreach ($vehicles as $other)
                    <flux:select.option value="{{ route('prototype.vehicles.history', $other['id']) }}" :selected="$other['id'] === $v['id']">{{ $other['name'] }}</flux:select.option>
                @endforeach
            </flux:select>

            @if ($entries->isNotEmpty())
                <flux:button icon="printer" :loading="false" class="print:hidden" x-data x-on:click="window.print()">Imprimir</flux:button>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div class="history">
        {{-- Al imprimir, la cabecera de página se oculta: este título la sustituye. --}}
        <p class="history__print-title hidden font-display text-3xl">{{ $v['name'] }} <span class="font-mono text-base">{{ $v['plate'] }}</span></p>

        @if ($entries->isEmpty())
            <x-empty-state title="Sin historial todavía.">
                <x-slot:actions>
                    <flux:button variant="primary" icon="plus" :href="route('prototype.rituals')" wire:navigate>Registrar ritual</flux:button>
                </x-slot:actions>
            </x-empty-state>
        @else
            <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1fr)_18rem]">
                <table class="ledger history__ledger">
                    <caption class="sr-only">Rituales de {{ $v['name'] }}</caption>
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Ritual</th>
                            <th scope="col" data-num>Kilómetros</th>
                            <th scope="col">Taller</th>
                            <th scope="col" data-num>Coste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr>
                                <td data-label="Fecha" class="history__date"><x-date :value="$entry['date']" /></td>
                                <td data-label="Ritual" class="history__task">{{ $taskTypes[$entry['task']]['name'] }}</td>
                                <td data-label="Kilómetros" data-num class="history__km"><x-km :value="$entry['km']" /></td>
                                <td data-label="Taller" class="history__shop">{{ $entry['workshop'] }}</td>
                                <td data-label="Coste" data-num class="history__cost"><x-money :amount="$entry['cost']" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total</td>
                            <td data-num><x-money :amount="$total" /></td>
                        </tr>
                    </tfoot>
                </table>

                {{-- En móvil los totales van primero: es lo que se quiere ver antes. --}}
                <aside class="grid gap-6 max-lg:order-first">
                    <x-ticket title="Gasto">
                        <x-ticket.row label="Este año"><x-money :amount="$thisYear" /></x-ticket.row>
                        @if ($v['cost_per_km'])
                            <x-ticket.row label="Por kilómetro"><span>{{ $v['cost_per_km'] }}</span></x-ticket.row>
                        @endif
                        <x-ticket.row label="Rituales"><span>{{ $entries->count() }}</span></x-ticket.row>
                        <x-ticket.total label="Total"><x-money :amount="$total" /></x-ticket.total>
                    </x-ticket>

                    <x-ticket title="Por tipo">
                        @foreach ($byType as $key => $amount)
                            <x-ticket.row :label="$taskTypes[$key]['name']"><x-money :amount="$amount" /></x-ticket.row>
                        @endforeach
                    </x-ticket>
                </aside>
            </div>
        @endif
    </div>
</x-layouts::prototype>
