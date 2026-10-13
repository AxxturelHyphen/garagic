{{--
    Alta y edición de vehículo. Mismo formulario para los dos (mode = create | edit).
    · Validación en el navegador con validatedForm (forms.js); el servidor sigue teniendo la última
      palabra: sus errores entran por serverErrors (@js($errors->messages())).
    · La carta de al lado se escribe mientras se rellena (vehicleForm, garage-form.js).
--}}
@php
    $isEdit = $mode === 'edit';
    $v = $isEdit ? $vehicles[(int) $vehicle] : null;
    $title = $isEdit ? 'Editar '.$v['name'] : 'Nuevo vehículo';
    $backHref = $isEdit ? route('prototype.vehicles.show', $v['id']) : route('prototype.deck');

    $numerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];

    // Vista previa: en la edición, la carta del vehículo; en el alta, una carta «Sin datos» con el
    // siguiente numeral. Los valores de relleno son los que el JS vuelve a escribir al teclear.
    $previewCard = $isEdit ? $v['card'] : [
        'numeral' => $numerals[count($vehicles)] ?? '',
        'title' => 'Tu vehículo',
        'plate' => '0000 XXX',
        'engraving' => 'car-libbey-1900',
        'back' => ['Kilómetros' => '0 km', 'Matrícula' => '0000 XXX', 'Próxima ITV' => 'Sin datos', 'Seguro hasta' => 'Sin datos'],
    ];
    $previewEmpty = ! $isEdit || $v['vitality'] === null;

    $initial = [
        'type' => $v['type'] ?? 'car',
        'plate' => $v['plate'] ?? '',
        'brand' => $v['brand'] ?? '',
        'model' => $v['model'] ?? '',
        'km' => isset($v['km']) ? (string) $v['km'] : '',
        'engravingBase' => asset('img/engravings'),
    ];

    // Reglas de cliente (comodidad). Las reglas de verdad son las del servidor. El año máximo es el de «hoy»
    // del prototipo; con datos reales será now()->year.
    $rules = [
        'plate' => ['required', 'plate'],
        'brand' => ['required', 'min:2'],
        'model' => ['required'],
        'year' => ['required', 'min:1950', 'max:'.\Illuminate\Support\Carbon::parse($today)->year],
        'km' => ['required', 'kmMin:0'],
        'last_itv' => ['date'],
        'last_insurance' => ['date'],
    ];
@endphp
<x-layouts::prototype :title="$title" :user="$user">
    <x-page-header :title="$title" :back="['href' => $backHref, 'label' => $isEdit ? $v['name'] : 'Baraja']" />

    <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1fr)_18rem]" x-data="vehicleForm(@js($initial))">
        {{-- En móvil la carta va primero (se ve qué se está creando); en escritorio, a la derecha y fija. --}}
        <div class="garage-sticky mx-auto w-44 sm:w-56 lg:col-start-2 lg:row-start-1 lg:w-full">
            <x-tarot-card data-vehicle-preview size="fluid" :card="$previewCard" :vitality="$isEdit ? ($v['vitality'] ?? 100) : 100" :empty="$previewEmpty" />
        </div>

        <x-panel variant="raised" class="lg:col-start-1 lg:row-start-1">
            {{-- novalidate: que los avisos sean los nuestros, no los globos del navegador. --}}
            <form method="POST" action="#" novalidate class="grid gap-6"
                  x-data="validatedForm(@js($rules), @js($errors->messages()))"
                  x-on:submit="submit($event); preventFakeSend($event)">
                @csrf

                <flux:radio.group name="type" variant="segmented" label="Tipo" x-model="type">
                    <flux:radio value="car" label="Coche" />
                    <flux:radio value="motorbike" label="Moto" />
                    <flux:radio value="van" label="Furgoneta" />
                </flux:radio.group>

                <div class="grid gap-6 sm:grid-cols-2">
                    <flux:field class="sm:col-span-2">
                        <flux:label>Matrícula</flux:label>
                        <flux:input name="plate" class:input="font-mono uppercase" placeholder="1234 BCD" autocomplete="off"
                                    x-model="plate" x-bind="field('plate')" />
                        <p id="plate-error" class="text-ink" x-show="hasError('plate')" x-text="error('plate')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Marca</flux:label>
                        <flux:input name="brand" x-model="brand" x-bind="field('brand')" />
                        <p id="brand-error" class="text-ink" x-show="hasError('brand')" x-text="error('brand')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Modelo</flux:label>
                        <flux:input name="model" x-model="model" x-bind="field('model')" />
                        <p id="model-error" class="text-ink" x-show="hasError('model')" x-text="error('model')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Año</flux:label>
                        <flux:input name="year" type="number" class:input="font-mono" :value="$v['year'] ?? ''" x-bind="field('year')" />
                        <p id="year-error" class="text-ink" x-show="hasError('year')" x-text="error('year')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Kilómetros</flux:label>
                        <flux:input name="km" inputmode="numeric" class:input="font-mono" x-model="km" x-bind="field('km')" />
                        <p id="km-error" class="text-ink" x-show="hasError('km')" x-text="error('km')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Última ITV <span class="ms-1.5 text-graphite">(opcional)</span></flux:label>
                        <flux:input name="last_itv" type="date" :value="$v['last_itv'] ?? ''" x-bind="field('last_itv')" />
                        <p id="last_itv-error" class="text-ink" x-show="hasError('last_itv')" x-text="error('last_itv')" x-cloak></p>
                    </flux:field>

                    <flux:field>
                        <flux:label>Pago del seguro <span class="ms-1.5 text-graphite">(opcional)</span></flux:label>
                        <flux:input name="last_insurance" type="date" :value="$v['last_insurance'] ?? ''" x-bind="field('last_insurance')" />
                        <p id="last_insurance-error" class="text-ink" x-show="hasError('last_insurance')" x-text="error('last_insurance')" x-cloak></p>
                    </flux:field>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <flux:button type="submit" variant="primary">Guardar vehículo</flux:button>
                    <flux:button variant="ghost" :href="$backHref" wire:navigate>Cancelar</flux:button>
                    <p role="status" class="text-graphite" x-show="saved" x-cloak>Vehículo guardado.</p>
                </div>
            </form>
        </x-panel>
    </div>

    @if ($isEdit)
        <x-panel class="mt-12">
            <flux:modal.trigger name="delete-vehicle">
                <flux:button variant="outline" icon="trash">Borrar vehículo</flux:button>
            </flux:modal.trigger>

            <flux:modal name="delete-vehicle" class="max-w-sm">
                <div class="grid gap-4">
                    <flux:heading size="lg">¿Borrar el {{ $v['name'] }}?</flux:heading>
                    <p>Dejará de aparecer en tu baraja. Su historial se conserva.</p>
                    <div class="flex justify-end gap-2">
                        <flux:modal.close><flux:button variant="ghost">Cancelar</flux:button></flux:modal.close>
                        <flux:button variant="danger" :href="route('prototype.deck')" wire:navigate>Borrar</flux:button>
                    </div>
                </div>
            </flux:modal>
        </x-panel>
    @endif
</x-layouts::prototype>
