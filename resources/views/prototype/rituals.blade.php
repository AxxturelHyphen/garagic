{{--
    Rituales: lo que toca y lo que ya está hecho, más el formulario para registrar uno nuevo.
    Pantalla rápida: la única animación es el plegable del formulario en móvil.

    Reparto (CLAUDE.md): todo lo que se ve y se filtra ocurre en el navegador (Alpine), porque las
    filas ya están en la página y no hace falta preguntar al servidor.
      · listFilter (ui.js) guarda los filtros activos y dice qué fila se ve.
      · ritualCounts (rituals-board.js) cuenta para las pestañas y oculta secciones vacías.
      · validatedForm (forms.js) avisa de errores del formulario antes de enviarlo.
    Datos de resources/views/prototype/_fake-data.php.
--}}
@php
    $urgent = ['overdue' => 0, 'soon' => 1];

    // Avisos con algo que hacer (vencido o próximo), el más urgente primero.
    $pending = collect($warnings)
        ->filter(fn (array $w): bool => isset($urgent[$w['status']]))
        ->sortBy(fn (array $w): int => $urgent[$w['status']])
        ->values();

    // Avisos sin urgencia: al día (el "sin rituales" del Clio no es un aviso de ninguna tarea).
    $upToDate = collect($warnings)->filter(fn (array $w): bool => $w['status'] === 'ok')->values();

    // Rituales hechos, el más reciente primero.
    $done = collect($rituals)->sortByDesc('date')->values();

    // Una entrada por fila para los contadores de Alpine: solo estado y vehículo.
    $rows = $pending->merge($upToDate)
        ->map(fn (array $w): array => ['status' => $w['status'], 'vehicle' => $w['vehicle_id']])
        ->merge($done->map(fn (array $r): array => ['status' => 'done', 'vehicle' => $r['vehicle_id']]))
        ->values()
        ->all();

    $tabs = ['all' => 'Todo', 'overdue' => 'Vencido', 'soon' => 'Próximo', 'ok' => 'Al día', 'done' => 'Hecho'];

    // Kilómetros actuales de cada vehículo, con formato español, como pista en el campo de km.
    $kmNow = collect($vehicles)->map(fn (array $v): string => number_format($v['km'], 0, ',', '.'))->all();
@endphp
<x-layouts::prototype title="Rituales" :user="$user">
    <x-page-header title="Rituales" />

    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_22rem]"
         x-data="{ ...listFilter({ status: 'all', vehicle: 'all' }), ...ritualCounts(@js($rows)) }">

        {{-- Formulario. Va primero en el HTML para que en móvil salga arriba; en escritorio pasa a la derecha. --}}
        <aside class="lg:col-start-2 lg:row-start-1 lg:sticky lg:top-8 lg:self-start"
               x-data="disclosure({ open: window.matchMedia('(min-width: 64rem)').matches })">
            <flux:button type="button" icon="plus" class="w-full lg:hidden" :loading="false"
                         x-on:click="toggle()" x-bind:aria-expanded="open" aria-controls="ritual-form-panel">
                Registrar ritual
            </flux:button>

            <div id="ritual-form-panel" x-show="open" x-collapse.duration.200ms x-cloak class="max-lg:pt-4">
                <x-panel variant="raised">
                    <h2 class="mb-5 font-display text-2xl max-lg:sr-only">Registrar ritual</h2>

                    {{--
                        novalidate: apaga los globos del navegador para que se vean nuestros mensajes.
                        Si todo cumple, validatedForm NO frena el envío y el servidor valida otra vez:
                        el cliente avisa, el servidor decide. Los errores del servidor entran por el
                        segundo argumento. Reglas propias (kmNotNegative, cost, notFuture): ver
                        rituals-form.js. Las demás vienen de forms.js.
                    --}}
                    <form method="POST" action="{{ route('prototype.rituals') }}" novalidate class="grid gap-5"
                          x-data="validatedForm({
                              vehicle_id: ['required'],
                              task_type: ['required'],
                              performed_on: ['required', 'date', 'notFuture'],
                              km: ['required', 'kmNotNegative'],
                              cost: ['required', 'cost'],
                          }, @js($errors->messages()))"
                          x-on:submit="
                              submit($event);
                              if (! $event.defaultPrevented) {
                                  {{-- Prototipo: no hay ruta que guarde. En la app real se borra este bloque y el formulario se envía. --}}
                                  $event.preventDefault();
                                  $dispatch('ritual-saved', Object.fromEntries(new FormData($el)));
                                  $dispatch('toast-show', { duration: 4000, slots: { text: 'Ritual guardado.' }, dataset: {} });
                                  $el.reset();
                                  submitted = false; touched = {}; errors = {};
                              }
                          ">
                        @csrf

                        <flux:field>
                            <flux:label>Vehículo</flux:label>
                            <flux:select name="vehicle_id" placeholder="Elige uno" x-bind="field('vehicle_id')">
                                @foreach ($vehicles as $vehicle)
                                    <flux:select.option value="{{ $vehicle['id'] }}">{{ $vehicle['name'] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <p id="vehicle_id-error" class="flex items-center gap-1.5" x-show="hasError('vehicle_id')" x-cloak aria-live="polite">
                                <flux:icon.triangle-alert variant="micro" /><span x-text="error('vehicle_id')"></span>
                            </p>
                        </flux:field>

                        <flux:field>
                            <flux:label>Tarea</flux:label>
                            <flux:select name="task_type" placeholder="Elige una" x-bind="field('task_type')">
                                @foreach ($taskTypes as $type)
                                    <flux:select.option value="{{ $type['key'] }}">{{ $type['name'] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <p id="task_type-error" class="flex items-center gap-1.5" x-show="hasError('task_type')" x-cloak aria-live="polite">
                                <flux:icon.triangle-alert variant="micro" /><span x-text="error('task_type')"></span>
                            </p>
                        </flux:field>

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                            <flux:field>
                                <flux:label>Fecha</flux:label>
                                <flux:input type="date" name="performed_on" x-bind="field('performed_on')" />
                                <p id="performed_on-error" class="flex items-center gap-1.5" x-show="hasError('performed_on')" x-cloak aria-live="polite">
                                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('performed_on')"></span>
                                </p>
                            </flux:field>

                            <flux:field>
                                <flux:label>Kilómetros</flux:label>
                                <flux:input type="number" name="km" inputmode="numeric" min="0" step="1" class:input="font-mono"
                                            x-bind="field('km')"
                                            x-bind:placeholder="@js($kmNow)[values.vehicle_id] ?? ''" />
                                <p id="km-error" class="flex items-center gap-1.5" x-show="hasError('km')" x-cloak aria-live="polite">
                                    <flux:icon.triangle-alert variant="micro" /><span x-text="error('km')"></span>
                                </p>
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Coste</flux:label>
                            <flux:input.group>
                                <flux:input type="number" name="cost" inputmode="decimal" min="0" step="0.01" class:input="font-mono"
                                            x-bind="field('cost')" />
                                <flux:input.group.suffix>€</flux:input.group.suffix>
                            </flux:input.group>
                            <p id="cost-error" class="flex items-center gap-1.5" x-show="hasError('cost')" x-cloak aria-live="polite">
                                <flux:icon.triangle-alert variant="micro" /><span x-text="error('cost')"></span>
                            </p>
                        </flux:field>

                        <flux:field>
                            <flux:label>Taller <span class="ms-1 text-graphite">(opcional)</span></flux:label>
                            <flux:input name="workshop" />
                        </flux:field>

                        <flux:button type="submit" variant="primary" :loading="false">Guardar</flux:button>
                    </form>
                </x-panel>
            </div>
        </aside>

        {{-- Filtros y listas --}}
        <div class="min-w-0 lg:col-start-1 lg:row-start-1">
            <flux:field class="mb-6 sm:max-w-xs">
                <flux:label>Vehículo</flux:label>
                <flux:select x-model="filters.vehicle">
                    <flux:select.option value="all">Todos</flux:select.option>
                    @foreach ($vehicles as $vehicle)
                        <flux:select.option value="{{ $vehicle['id'] }}">{{ $vehicle['name'] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            {{-- Son filtros de una lista, no pestañas de contenido: botones con aria-pressed. --}}
            <div role="group" aria-label="Estado" class="mb-8 flex flex-wrap gap-x-6 border-b border-hairline">
                @foreach ($tabs as $status => $name)
                    <button type="button"
                            class="-mb-px cursor-pointer border-b border-transparent py-2 text-graphite aria-pressed:border-ink aria-pressed:text-ink"
                            x-on:click="set('status', '{{ $status }}')"
                            x-bind:aria-pressed="is('status', '{{ $status }}')"
                            aria-pressed="{{ $status === 'all' ? 'true' : 'false' }}">
                        {{ $name }}
                        <span class="ms-1 font-mono text-sm tabular-nums" x-text="count('{{ $status }}')">{{ $status === 'all' ? count($rows) : collect($rows)->where('status', $status)->count() }}</span>
                    </button>
                @endforeach
            </div>

            <div class="grid gap-10">
                <section x-show="visibleIn(['overdue', 'soon']) > 0">
                    <h2 class="mb-3 font-display text-2xl">Pendiente</h2>
                    <ul role="list" class="border-t border-hairline">
                        @foreach ($pending as $warning)
                            @php($type = $taskTypes[$warning['task']])
                            <x-ritual-row :status="$warning['status']" :label="$warning['label']"
                                          :task="$type['name']" :icon="$type['icon']" :vehicle="$vehicles[$warning['vehicle_id']]['name']"
                                          :date="$warning['due_date']" :km="$warning['due_km']"
                                          x-show="matches({ status: '{{ $warning['status'] }}', vehicle: {{ $warning['vehicle_id'] }} })" />
                        @endforeach
                    </ul>
                </section>

                <section x-show="visibleIn(['ok']) > 0">
                    <h2 class="mb-3 font-display text-2xl">Al día</h2>
                    <ul role="list" class="border-t border-hairline">
                        @foreach ($upToDate as $warning)
                            @php($type = $taskTypes[$warning['task']])
                            <x-ritual-row status="ok" :label="$warning['label']"
                                          :task="$type['name']" :icon="$type['icon']" :vehicle="$vehicles[$warning['vehicle_id']]['name']"
                                          :date="$warning['due_date']" :km="$warning['due_km']"
                                          x-show="matches({ status: 'ok', vehicle: {{ $warning['vehicle_id'] }} })" />
                        @endforeach
                    </ul>
                </section>

                <section x-show="visibleIn(['done']) > 0">
                    <h2 class="mb-3 font-display text-2xl">Hecho</h2>
                    <ul role="list" class="border-t border-hairline">
                        @foreach ($done as $ritual)
                            @php($type = $taskTypes[$ritual['task']])
                            <x-ritual-row status="done"
                                          :task="$type['name']" :icon="$type['icon']" :vehicle="$vehicles[$ritual['vehicle_id']]['name']"
                                          :date="$ritual['date']" :cost="$ritual['cost']"
                                          x-show="matches({ status: 'done', vehicle: {{ $ritual['vehicle_id'] }} })" />
                        @endforeach
                    </ul>
                </section>

                <p class="text-graphite" x-show="isEmpty()" x-cloak>Nada en esta lista.</p>
            </div>
        </div>
    </div>
</x-layouts::prototype>
