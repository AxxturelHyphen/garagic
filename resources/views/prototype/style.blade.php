{{--
    Guía de estilo: colores, tipos, iconos y cada pieza en cada estado. Es el banco de pruebas del diseño.
    Datos de resources/views/prototype/_fake-data.php. Texto mínimo: aquí se mira, no se lee.
--}}
@php
    $colors = [
        ['Hueso', 'paper', '#E4DFD3', '#1B1916', 'Fondo'],
        ['Cartulina', 'card', '#F0ECE2', '#25221E', 'Carta, ticket, campos'],
        ['Tinta', 'ink', '#221F1B', '#E4DFD3', 'Texto y filetes'],
        ['Grafito', 'graphite', '#5F5A51', '#A39D90', 'Texto secundario'],
        ['Plomo', 'rule', '#7D776A', '#6F695E', 'Bordes de campos'],
        ['Filete', 'hairline', '#C9C2B2', '#3A362F', 'Separadores'],
        ['Bermellón', 'vermilion', '#B0341A', '#E0583A', 'Solo el sello IA'],
    ];
    $zinc = ['50' => '#F3EFE6', '100' => '#EAE5DA', '200' => '#D6CFC0', '300' => '#BDB6A6', '400' => '#8F897C', '500' => '#5F5A51', '600' => '#4A463F', '700' => '#38342E', '800' => '#2A2723', '900' => '#221F1B', '950' => '#161411'];

    $icons = ['droplet', 'disc-3', 'wrench', 'shield-check', 'clipboard-check', 'receipt-text', 'triangle-alert', 'clock', 'check', 'circle-dashed', 'scroll-text', 'gauge', 'stamp', 'plus', 'pencil', 'trash', 'printer', 'arrow-left', 'chevron-left', 'chevron-right', 'star', 'ship-wheel', 'castle', 'menu', 'x', 'chevron-down', 'chevrons-up-down', 'minus', 'eye', 'eye-off', 'settings', 'log-out', 'house', 'search', 'sun', 'moon', 'monitor'];

    // Una misma carta (el Seat Ibiza, con la foto de David) a cuatro vitalidades, y la de «sin datos».
    $wear = [
        ['card' => $vehicles[1]['card'], 'vitality' => 95, 'empty' => false, 'caption' => '95'],
        ['card' => $vehicles[1]['card'], 'vitality' => 72, 'empty' => false, 'caption' => '72'],
        ['card' => $vehicles[1]['card'], 'vitality' => 45, 'empty' => false, 'caption' => '45'],
        ['card' => $vehicles[1]['card'], 'vitality' => 30, 'empty' => false, 'caption' => '30'],
        ['card' => $vehicles[4]['card'], 'vitality' => 100, 'empty' => true, 'caption' => 'Sin datos'],
    ];

    // La mano: los 4 vehículos, con el que más atención necesita al frente. El sello se estampa en el Seat.
    $deckCards = collect($vehicles)->values()->map(fn (array $v): array => [
        'card' => $v['card'],
        'vitality' => $v['vitality'] ?? 100,
        'empty' => $v['vitality'] === null,
        'label' => $v['name'],
    ])->all();
    $deckCards[0]['stamp'] = ['name' => $achievements[1]['name'], 'emblem' => $emblems['ten-rituals'], 'tilt' => '-7deg', 'fresh' => true];
    $deckActive = array_search($deckFocus, array_keys($vehicles), true);

    $statusRows = [
        ['status' => 'overdue', 'task' => 'ITV', 'icon' => 'clipboard-check', 'label' => 'Vencida hace 14 días', 'date' => '2026-09-28'],
        ['status' => 'soon', 'task' => 'Seguro', 'icon' => 'shield-check', 'label' => 'Vence en 9 días', 'date' => '2026-10-21'],
        ['status' => 'ok', 'task' => 'Cambio de aceite', 'icon' => 'droplet', 'label' => 'Faltan 6.580 km', 'km' => 75000],
        ['status' => 'done', 'task' => 'Neumáticos', 'icon' => 'disc-3', 'date' => '2026-04-11', 'cost' => 236.00],
        ['status' => 'none', 'task' => 'Renault Clio', 'icon' => 'circle-dashed', 'label' => 'Sin rituales registrados'],
    ];
@endphp
<x-layouts::prototype title="Guía de estilo" :user="$user">
    <x-page-header title="Guía de estilo" />

    <nav aria-label="Secciones" class="-mt-2 mb-10 flex flex-wrap gap-x-5 gap-y-1">
        @foreach (['color' => 'Color', 'tipos' => 'Tipos', 'iconos' => 'Iconos', 'carta' => 'Carta', 'mano' => 'Mano', 'vitalidad' => 'Vitalidad', 'urgencia' => 'Urgencia', 'ia' => 'IA', 'logros' => 'Logros', 'cifras' => 'Cifras', 'formularios' => 'Formularios', 'navegacion' => 'Navegación', 'movimiento' => 'Movimiento'] as $anchor => $name)
            <a href="#{{ $anchor }}" class="text-graphite hover:text-ink">{{ $name }}</a>
        @endforeach
    </nav>

    <div class="grid gap-14">
        {{-- 1. Color --}}
        <x-panel title="Color" id="color" class="scroll-mt-6">
            <ul role="list" class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-7">
                @foreach ($colors as [$name, $token, $light, $dark, $use])
                    <li>
                        <div class="h-20 border border-ink" style="background-color: var(--{{ $token }})"></div>
                        <p class="mt-2">{{ $name }}</p>
                        <p class="font-mono text-xs text-graphite">{{ $light }}</p>
                        <p class="font-mono text-xs text-graphite">{{ $dark }}</p>
                        <p class="text-graphite">{{ $use }}</p>
                    </li>
                @endforeach
            </ul>

            <ul role="list" class="mt-6 flex flex-wrap gap-2">
                @foreach ($zinc as $step => $hex)
                    <li class="w-16">
                        <div class="h-8 border border-ink" style="background: {{ $hex }}"></div>
                        <p class="font-mono text-xs text-graphite">{{ $step }}</p>
                    </li>
                @endforeach
            </ul>
        </x-panel>

        {{-- 2. Tipos --}}
        <x-panel title="Tipos" id="tipos" class="scroll-mt-6">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="grid content-start gap-3">
                    <p class="text-graphite">Jellyka Saint Andrews Queen</p>
                    <p class="font-display text-5xl leading-tight">Tu garaje, en una baraja.</p>
                    <p class="font-display text-3xl">áéíóúñ ¿¡ €</p>
                    <p class="font-display text-4xl">I II III IV V VI VII X XVI</p>
                    <p class="font-display text-5xl">95 72 30 214.800</p>
                </div>

                <div class="grid content-start gap-3">
                    <p class="text-graphite">IM Fell English</p>
                    <p class="text-lg">Garagic te avisa de la ITV, el seguro, el aceite y las ruedas, y apunta lo que te cuesta cada vehículo.</p>
                    <p>Dieciséis píxeles como mínimo: áéíóúñ ¿Qué? ¡Hecho!</p>
                    <p class="italic text-graphite">Cursiva: vence en 9 días.</p>
                </div>

                <div class="grid content-start gap-3">
                    <p class="text-graphite">IBM Plex Mono</p>
                    <x-km :value="214800" class="text-2xl" />
                    <x-money :amount="819.40" class="text-2xl" />
                    <x-date value="2026-10-12" class="text-2xl" />
                    <p class="font-mono text-2xl tracking-wider">4821 LKT</p>
                </div>
            </div>
        </x-panel>

        {{-- 3. Iconos --}}
        <x-panel title="Iconos" id="iconos" class="scroll-mt-6">
            <ul role="list" class="grid grid-cols-3 gap-x-4 gap-y-5 sm:grid-cols-5 lg:grid-cols-8">
                @foreach ($icons as $icon)
                    <li class="grid justify-items-start gap-1">
                        <div class="flex items-center gap-3">
                            <flux:icon :icon="$icon" class="size-6" />
                            <flux:icon :icon="$icon" class="size-4" />
                        </div>
                        <span class="font-mono text-xs text-graphite">{{ $icon }}</span>
                    </li>
                @endforeach
            </ul>
        </x-panel>

        {{-- 4. La carta --}}
        <x-panel title="Carta" id="carta" class="scroll-mt-6">
            <ul role="list" class="grid grid-cols-2 gap-x-5 gap-y-6 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($wear as $item)
                    <li>
                        <x-tarot-card :card="$item['card']" :vitality="$item['vitality']" :empty="$item['empty']" />
                        <p class="mt-3 text-center font-mono text-sm">{{ $item['caption'] }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-10 grid grid-cols-2 items-start gap-x-5 gap-y-8 sm:grid-cols-4">
                <div>
                    <x-tarot-card :card="$vehicles[2]['card']" :vitality="72" />
                    <p class="mt-3 text-center">Grabado</p>
                </div>
                <div>
                    <x-tarot-card :card="$vehicles[3]['card']" :vitality="30" :flipped="true" />
                    <p class="mt-3 text-center">Dorso</p>
                </div>
                <div>
                    <x-tarot-card :card="['numeral' => '0', 'title' => 'Garagic']" :flipped="true" :flippable="false" :tilt="false" />
                    <p class="mt-3 text-center">Boca abajo</p>
                </div>
                <div x-data="{ on: true }">
                    <template x-if="on">
                        <x-tarot-card :card="$arcana['showcase'] + ['title' => $arcana['showcase']['name'], 'arcana' => $arcana['showcase']['image']]" :entrance="true" />
                    </template>
                    <div class="mt-3 flex justify-center">
                        <flux:button size="sm" :loading="false" x-on:click="on = false; $nextTick(() => on = true)">Repartir</flux:button>
                    </div>
                </div>
            </div>

            <ul role="list" class="mt-10 flex flex-wrap items-end gap-6">
                <li><x-tarot-card size="sm" :card="$vehicles[1]['card']" /></li>
                <li><x-tarot-card size="md" :card="$vehicles[1]['card']" /></li>
                <li><x-tarot-card size="lg" :card="$vehicles[1]['card']" /></li>
            </ul>

            <div class="dark mt-10 bg-paper p-6 text-ink">
                <ul role="list" class="grid grid-cols-2 gap-x-5 gap-y-6 sm:grid-cols-3 lg:grid-cols-5">
                    @foreach ($wear as $item)
                        <li>
                            <x-tarot-card :card="$item['card']" :vitality="$item['vitality']" :empty="$item['empty']" />
                            <p class="mt-3 text-center font-mono text-sm">{{ $item['caption'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </x-panel>

        {{-- 5. La mano --}}
        <x-panel title="Mano" id="mano" class="scroll-mt-6">
            <x-tarot-deck :cards="$deckCards" :active="$deckActive">
                <x-slot:aside>
                    @foreach ($deckCards as $i => $item)
                        <div x-show="isActive({{ $i }})" @if ($i !== $deckActive) x-cloak @endif x-transition.opacity.duration.200ms>
                            <x-vitality-bar :value="$vehicles[$i + 1]['vitality']" />
                        </div>
                    @endforeach
                </x-slot:aside>
            </x-tarot-deck>
        </x-panel>

        {{-- 6. Vitalidad --}}
        <x-panel title="Vitalidad" id="vitalidad" class="scroll-mt-6">
            <div class="grid gap-8 md:grid-cols-2">
                <x-vitality-bar :value="95" />
                <x-vitality-bar :value="72" />
                <x-vitality-bar :value="45" />
                <x-vitality-bar :value="30" />
                <x-vitality-bar :value="95" size="lg" />
                <x-vitality-bar :value="null" size="lg" :href="route('prototype.rituals')" />
            </div>
        </x-panel>

        {{-- 7. Urgencia --}}
        <x-panel title="Urgencia" id="urgencia" class="scroll-mt-6">
            <div class="flex flex-wrap gap-4">
                <x-status-tag tone="strong">Vencida hace 14 días</x-status-tag>
                <x-status-tag tone="medium">Vence en 9 días</x-status-tag>
                <x-status-tag tone="calm">Al día</x-status-tag>
                <x-status-tag tone="done">Hecho</x-status-tag>
                <x-status-tag tone="empty">Sin datos</x-status-tag>
            </div>

            <ul role="list" class="mt-8 border-t border-hairline">
                @foreach ($statusRows as $row)
                    <x-ritual-row :status="$row['status']" :task="$row['task']" :icon="$row['icon']" :label="$row['label'] ?? null"
                                  :date="$row['date'] ?? null" :km="$row['km'] ?? null" :cost="$row['cost'] ?? null"
                                  :vehicle="$row['status'] === 'none' ? null : 'Citroën Berlingo'" />
                @endforeach
            </ul>
        </x-panel>

        {{-- 8. IA --}}
        <x-panel title="IA" id="ia" class="scroll-mt-6">
            <div class="flex flex-wrap items-center gap-6">
                <x-ai-seal size="sm" />
                <x-ai-seal size="md" />
                <x-ai-seal size="md" label />
            </div>

            <div class="mt-8 grid max-w-3xl gap-6">
                <x-arcana-card urgency="low" summary="Es una factura de mantenimiento rutinario. No hay nada urgente." />
                <x-arcana-card urgency="medium" summary="Suele ser la batería o las bujías. Puedes seguirla usando, pero no lo dejes más de dos semanas." />
                <x-arcana-card urgency="high" summary="Es probable que las pastillas estén al límite. Afecta a la frenada: revísalo cuanto antes." />
            </div>
        </x-panel>

        {{-- 9. Logros --}}
        <x-panel title="Logros" id="logros" class="scroll-mt-6">
            <div class="flex flex-wrap items-start gap-8" x-data="{ on: true }">
                @foreach ($achievements as $achievement)
                    <x-achievement-stamp :name="$achievement['name']" :description="$achievement['description']"
                                         :emblem="$emblems[$achievement['key']] ?? null" :icon="$achievement['icon']"
                                         :unlocked="$achievement['unlocked']" :date="$achievement['date']" :hint="$achievement['hint']"
                                         :tilt="$achievement['tilt']" />
                @endforeach

                <x-achievement-stamp size="sm" :name="$achievements[0]['name']" :description="$achievements[0]['description']"
                                     :emblem="$emblems['first-ritual']" :date="$achievements[0]['date']" tilt="-3deg" />

                <div class="grid justify-items-start gap-3">
                    <template x-if="on">
                        <x-achievement-stamp :name="$achievements[1]['name']" :emblem="$emblems['ten-rituals']" :fresh="true" tilt="2deg" size="sm" />
                    </template>
                    <flux:button size="sm" :loading="false" x-on:click="on = false; $nextTick(() => on = true)">Estampar</flux:button>
                </div>
            </div>
        </x-panel>

        {{-- 10. Cifras --}}
        <x-panel title="Cifras" id="cifras" class="scroll-mt-6">
            <div class="grid items-start gap-10 md:grid-cols-[18rem_minmax(0,1fr)]">
                <x-ticket title="Gasto">
                    <x-ticket.row label="Este año"><x-money :amount="683.20" /></x-ticket.row>
                    <x-ticket.row label="Por km"><span>0,04 €/km</span></x-ticket.row>
                    <x-ticket.row label="Rituales"><span>4</span></x-ticket.row>
                    <x-ticket.total label="Total"><x-money :amount="819.40" /></x-ticket.total>
                </x-ticket>

                <div class="grid grid-cols-2 gap-6">
                    <x-stat label="Kilómetros" :value="'214.800 km'" />
                    <x-stat label="Gasto" :value="'819,40 €'" />
                    <x-stat label="Rituales" value="4" variant="display" />
                    <x-stat label="Sellos" value="2 de 4" variant="display" />
                </div>
            </div>
        </x-panel>

        {{-- 11. Formularios --}}
        <x-panel title="Formularios" id="formularios" class="scroll-mt-6">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <flux:field>
                    <flux:label>Matrícula</flux:label>
                    <flux:input class:input="font-mono" placeholder="1234 MNP" />
                </flux:field>

                <flux:field>
                    <flux:label>Kilómetros</flux:label>
                    <flux:input class:input="font-mono" inputmode="numeric" value="68420" />
                    <flux:description>Los que marca el cuentakilómetros.</flux:description>
                </flux:field>

                <flux:field>
                    <flux:label>Matrícula</flux:label>
                    <flux:input class:input="font-mono" value="12" :invalid="true" />
                    <flux:error message="Escribe una matrícula válida." />
                </flux:field>

                <flux:field>
                    <flux:label>Deshabilitado</flux:label>
                    <flux:input value="Seat Ibiza" disabled />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha</flux:label>
                    <flux:input type="date" value="2026-10-12" />
                </flux:field>

                <flux:field>
                    <flux:label>Tipo de tarea</flux:label>
                    <flux:select>
                        <flux:select.option>Cambio de aceite</flux:select.option>
                        <flux:select.option>ITV</flux:select.option>
                        <flux:select.option>Seguro</flux:select.option>
                    </flux:select>
                </flux:field>

                <flux:field class="md:col-span-2 xl:col-span-3">
                    <flux:label>Notas</flux:label>
                    <flux:textarea rows="2" />
                </flux:field>

                <div class="grid content-start gap-3">
                    <flux:checkbox label="Recordarme" checked />
                    <flux:checkbox label="Avisarme por correo" />
                </div>

                <flux:radio.group x-data="{ mode: 'symptom' }" x-model="mode" variant="segmented" label="Modo">
                    <flux:radio value="symptom" label="Síntoma" />
                    <flux:radio value="invoice" label="Factura" />
                </flux:radio.group>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <flux:button variant="primary">Guardar</flux:button>
                <flux:button variant="outline">Cancelar</flux:button>
                <flux:button variant="ghost">Volver</flux:button>
                <flux:button variant="danger">Borrar</flux:button>
                <flux:button disabled>Deshabilitado</flux:button>

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Menú</flux:button>
                    <flux:menu>
                        <flux:menu.item icon="pencil">Editar</flux:menu.item>
                        <flux:menu.item icon="printer">Imprimir</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item icon="trash">Borrar</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                <flux:modal.trigger name="guia-modal">
                    <flux:button>Modal</flux:button>
                </flux:modal.trigger>

                {{-- Flux escucha el evento `toast-show` (lo mismo que dispara Flux::toast() desde PHP). --}}
                <flux:button x-data x-on:click="$dispatch('toast-show', { duration: 4000, slots: { text: 'Ritual guardado.' }, dataset: {} })">Aviso</flux:button>
            </div>

            <flux:modal name="guia-modal" class="max-w-sm">
                <div class="grid gap-4">
                    <flux:heading size="lg">Borrar vehículo</flux:heading>
                    <p>Se borrarán también sus rituales.</p>
                    <div class="flex justify-end gap-2">
                        <flux:modal.close><flux:button variant="ghost">Cancelar</flux:button></flux:modal.close>
                        <flux:button variant="danger">Borrar</flux:button>
                    </div>
                </div>
            </flux:modal>
        </x-panel>

        {{-- 12. Navegación --}}
        <x-panel title="Navegación" id="navegacion" class="scroll-mt-6">
            <div class="flex flex-wrap items-center gap-8">
                <x-nav-link href="#navegacion" :current="true">Baraja</x-nav-link>
                <x-nav-link href="#navegacion" :current="false">Rituales</x-nav-link>
            </div>

            <div class="mt-8" x-data="tabs({ initial: 'tipos' })">
                <div role="tablist" aria-label="Catálogo" class="flex gap-6 border-b border-hairline" x-on:keydown="onKeydown($event)">
                    @foreach (['tipos' => 'Tipos de tarea', 'logros' => 'Logros'] as $id => $name)
                        <button type="button" role="tab" data-tab="{{ $id }}" id="tab-{{ $id }}" aria-controls="panel-{{ $id }}"
                                x-bind:aria-selected="isActive('{{ $id }}')" x-bind:tabindex="isActive('{{ $id }}') ? 0 : -1"
                                x-on:click="select('{{ $id }}')"
                                class="-mb-px cursor-pointer border-b border-transparent py-2 text-graphite aria-selected:border-ink aria-selected:text-ink">{{ $name }}</button>
                    @endforeach
                </div>
                <div id="panel-tipos" role="tabpanel" aria-labelledby="tab-tipos" x-show="isActive('tipos')" x-transition.opacity.duration.200ms class="pt-4">ITV, Seguro, Aceite, Neumáticos.</div>
                <div id="panel-logros" role="tabpanel" aria-labelledby="tab-logros" x-show="isActive('logros')" x-cloak x-transition.opacity.duration.200ms class="pt-4">Primer ritual, 10 rituales.</div>
            </div>

            <div class="mt-8 grid gap-8 md:grid-cols-3">
                <x-panel title="Plano"><p class="text-graphite">Solo un filete.</p></x-panel>
                <x-panel title="Alzado" variant="raised"><p class="text-graphite">Cartulina y filete.</p></x-panel>
                <x-panel title="Doble" variant="double"><p class="text-graphite">Doble filete.</p></x-panel>
            </div>

            <x-empty-state title="Nada por aquí">
                Cuando haya algo, saldrá en esta lista.
                <x-slot:actions><flux:button size="sm" icon="plus">Añadir</flux:button></x-slot:actions>
            </x-empty-state>

            <table class="ledger mt-4">
                <thead>
                    <tr><th>Fecha</th><th>Ritual</th><th data-num>Km</th><th data-num>Coste</th></tr>
                </thead>
                <tbody>
                    @php($ledgerRows = array_slice($rituals, 7, 3))
                    @foreach ($ledgerRows as $ritual)
                        <tr>
                            <td data-label="Fecha"><x-date :value="$ritual['date']" /></td>
                            <td data-label="Ritual">{{ $taskTypes[$ritual['task']]['name'] }}</td>
                            <td data-label="Km" data-num>{{ number_format($ritual['km'], 0, ',', '.') }}</td>
                            <td data-label="Coste" data-num>{{ number_format($ritual['cost'], 2, ',', '.') }}&nbsp;€</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="3">Total</td><td data-num>{{ number_format(array_sum(array_column($ledgerRows, 'cost')), 2, ',', '.') }}&nbsp;€</td></tr>
                </tfoot>
            </table>
        </x-panel>

        {{-- 13. Movimiento --}}
        <x-panel title="Movimiento" id="movimiento" class="scroll-mt-6">
            <table class="ledger">
                <thead>
                    <tr><th>Familia</th><th>Qué</th><th data-num>Duración</th></tr>
                </thead>
                <tbody>
                    <tr><td data-label="Familia">Carta</td><td data-label="Qué">Giro, inclinación, mano</td><td data-label="Duración" data-num>300 ms</td></tr>
                    <tr><td data-label="Familia">Reparto</td><td data-label="Qué">Entrada de la carta y de la mano</td><td data-label="Duración" data-num>700 ms</td></tr>
                    <tr><td data-label="Familia">Sello</td><td data-label="Qué">Estampado</td><td data-label="Duración" data-num>250 ms</td></tr>
                    <tr><td data-label="Familia">Barra</td><td data-label="Qué">Relleno de la vitalidad</td><td data-label="Duración" data-num>300 ms</td></tr>
                    <tr><td data-label="Familia">Interfaz</td><td data-label="Qué">Pestañas, plegables, subrayado</td><td data-label="Duración" data-num>200 ms</td></tr>
                </tbody>
            </table>
            <p class="mt-4 text-graphite">Con «reducir movimiento» todo queda quieto y en su estado final.</p>
        </x-panel>
    </div>
</x-layouts::prototype>
