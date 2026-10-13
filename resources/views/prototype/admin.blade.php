{{--
    Catálogo (solo admin): tipos de tarea y logros que comparten todos los usuarios.
    Pestañas (ui.js: tabs) + lista de libro mayor + formulario de alta/edición (forms.js: validatedForm)
    + un modal de confirmación para borrar. La lógica de la pantalla está en js/garagic/admin-catalog.js.
    Datos de resources/views/prototype/_fake-data.php ($taskTypes, $achievements). Todo es simulado:
    nada se guarda; en la app real cada acción será de Livewire.
--}}
@php
    // Iconos que puede elegir el admin para un tipo de tarea (lista cerrada: son los Lucide ya instalados).
    $iconOptions = [
        'clipboard-check' => 'Inspección',
        'shield-check' => 'Seguro',
        'droplet' => 'Aceite',
        'disc-3' => 'Ruedas',
        'wrench' => 'Taller',
        'gauge' => 'Kilómetros',
        'receipt-text' => 'Gasto',
    ];

    // Tipos de tarea: lo que el formulario necesita para rellenarse al pulsar «Editar» (mismos nombres que sus campos).
    $typeItems = collect($taskTypes)->mapWithKeys(fn (array $type): array => [$type['key'] => [
        'name' => $type['name'],
        'interval_months' => (string) ($type['interval_months'] ?? ''),
        'interval_km' => (string) ($type['interval_km'] ?? ''),
        'icon' => $type['icon'],
    ]])->all();

    // Logros. Los campos llevan el prefijo «achievement_»/«rule_» porque las dos pestañas conviven en una
    // sola página y un `name` repetido repetiría también los id de los errores (aria-describedby).
    $achievementItems = collect($achievements)->mapWithKeys(fn (array $achievement): array => [$achievement['key'] => [
        'achievement_name' => $achievement['name'],
        'achievement_description' => $achievement['description'],
        'rule' => $achievement['rule'],
        'rule_value' => (string) ($achievement['value'] ?? ''),
    ]])->all();

    // Reglas de logro (las del catálogo) y cuáles piden un número N.
    $rules = collect($achievements)->pluck('rule_label', 'rule')->all();
    $rulesWithNumber = collect($achievements)->whereNotNull('value')->pluck('rule')->values()->all();

    // Qué dice el modal al borrar cada elemento y el aviso posterior.
    $deleteTargets = [];
    foreach ($taskTypes as $type) {
        $deleteTargets['type:'.$type['key']] = [
            'name' => $type['name'],
            'note' => 'Los rituales ya registrados con este tipo se conservan.',
            'done' => 'Tipo de tarea borrado.',
        ];
    }
    foreach ($achievements as $achievement) {
        $deleteTargets['achievement:'.$achievement['key']] = [
            'name' => $achievement['name'],
            'note' => '',
            'done' => 'Logro borrado.',
        ];
    }

    // Las cifras del texto van en mono (los dígitos de IM Fell son «de estilo antiguo»: el 1 parece una I).
    // Se escapa el texto y se envuelve cada número sin tocar las entidades HTML (&#039; contiene dígitos).
    $mono = fn (string $text): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString(
        preg_replace_callback(
            '/&#?\w+;|\d[\d.,\/]*\d|\d/u',
            fn (array $match): string => str_starts_with($match[0], '&') ? $match[0] : '<span class="font-mono text-[0.9em] tabular-nums">'.$match[0].'</span>',
            e($text),
        ),
    );
    $ruleLabel = fn (string $label, ?int $value): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString(
        preg_replace('/\bN\b/', $value === null ? 'N' : '<span class="font-mono tabular-nums">'.$value.'</span>', e($label)),
    );
@endphp
<x-layouts::prototype title="Catálogo" :user="$admin">
    <x-page-header title="Catálogo" />

    {{-- Raíz: qué se está borrando y el modal de confirmación (uno para las dos pestañas). --}}
    <div x-data="catalogAdmin(@js($deleteTargets))">
        <div x-data="tabs({ initial: 'types' })">
            <div role="tablist" aria-label="Catálogo" class="flex gap-6 border-b border-hairline" x-on:keydown="onKeydown($event)">
                @foreach (['types' => 'Tipos de tarea', 'achievements' => 'Logros'] as $id => $name)
                    <button type="button" role="tab" data-tab="{{ $id }}" id="tab-{{ $id }}" aria-controls="panel-{{ $id }}"
                            x-bind:aria-selected="isActive('{{ $id }}')" x-bind:tabindex="isActive('{{ $id }}') ? 0 : -1"
                            x-on:click="select('{{ $id }}')"
                            class="-mb-px cursor-pointer border-b border-transparent py-2 text-graphite aria-selected:border-ink aria-selected:text-ink">{{ $name }}</button>
                @endforeach
            </div>

            {{-- ═══ Pestaña: tipos de tarea ═══ --}}
            <section id="panel-types" role="tabpanel" aria-labelledby="tab-types" class="pt-6"
                     x-show="isActive('types')" x-transition.opacity.duration.200ms
                     x-data="catalogPanel({ kind: 'type', items: @js($typeItems), blank: @js(['name' => '', 'interval_months' => '', 'interval_km' => '', 'icon' => 'wrench']), saved: 'Tipo de tarea guardado.' })">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">
                    <div>
                        <div class="mb-4 lg:hidden">
                            <flux:button size="sm" icon="plus" :loading="false" x-on:click="startNew(true)">Nuevo tipo de tarea</flux:button>
                        </div>

                        <table class="ledger catalog-ledger">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cada</th>
                                    <th data-num>Usos</th>
                                    <th><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taskTypes as $type)
                                    <tr x-show="!isRemoved('type:{{ $type['key'] }}')" x-transition.opacity.duration.200ms>
                                        <td class="catalog__name">
                                            <span class="flex items-center gap-3">
                                                <flux:icon :icon="$type['icon']" class="size-5 shrink-0 text-graphite" />
                                                {{ $type['name'] }}
                                            </span>
                                        </td>
                                        <td class="catalog__every" data-label="Cada">
                                            @if ($type['interval_months'])<span class="font-mono tabular-nums">{{ $type['interval_months'] }}</span> {{ $type['interval_months'] === 1 ? 'mes' : 'meses' }}@endif
                                            @if ($type['interval_months'] && $type['interval_km']) o @endif
                                            @if ($type['interval_km'])<x-km :value="$type['interval_km']" />@endif
                                        </td>
                                        <td class="catalog__uses" data-label="Usos" data-num>{{ $type['uses'] }}</td>
                                        <td class="catalog__actions">
                                            <div>
                                                <flux:button size="sm" variant="ghost" icon="pencil" :loading="false" aria-label="Editar {{ $type['name'] }}"
                                                             x-on:click="startEdit('{{ $type['key'] }}')">Editar</flux:button>
                                                <flux:button size="sm" variant="ghost" icon="trash" :loading="false" aria-label="Borrar {{ $type['name'] }}"
                                                             x-on:click="askDelete('type:{{ $type['key'] }}')">Borrar</flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <x-panel variant="raised" class="scroll-mt-4" x-ref="formPanel" x-show="formOpen || wide" x-collapse.duration.200ms x-cloak>
                        <h2 class="mb-4 font-display text-2xl" x-text="editing ? 'Editar tipo de tarea' : 'Nuevo tipo de tarea'">Nuevo tipo de tarea</h2>

                        {{-- El formulario va en un x-for de UN elemento con clave `session`: cada vez que se pulsa
                             Nuevo o Editar la clave cambia, Alpine lo recrea y validatedForm empieza sin errores. --}}
                        <template x-for="id in [session]" :key="id">
                            <form method="POST" action="#" novalidate class="grid gap-5"
                                  x-data="validatedForm({ name: ['required', 'max:60'], interval_months: ['min:1', 'max:120'], interval_km: ['kmMin:1'] }, @js($errors->messages()))"
                                  x-on:submit="submit($event); finish($event)">
                                <flux:field>
                                    <flux:label>Nombre</flux:label>
                                    <flux:input name="name" autocomplete="off" x-model="draft.name" x-bind="field('name')" />
                                    <p id="name-error" class="flex items-start gap-1.5" x-show="hasError('name')" x-cloak>
                                        <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('name')"></span>
                                    </p>
                                </flux:field>

                                {{-- Meses o kilómetros: con uno basta. «Al menos uno» lo comprueba catalogPanel.crossProblem. --}}
                                <div class="grid gap-5" x-on:input="crossError = ''">
                                    <flux:field>
                                        <flux:label>Cada cuántos meses</flux:label>
                                        <flux:input type="number" name="interval_months" min="1" inputmode="numeric" class:input="font-mono" x-model="draft.interval_months" x-bind="field('interval_months')" />
                                        <p id="interval_months-error" class="flex items-start gap-1.5" x-show="hasError('interval_months')" x-cloak>
                                            <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('interval_months')"></span>
                                        </p>
                                    </flux:field>

                                    <flux:field>
                                        <flux:label>Cada cuántos kilómetros</flux:label>
                                        <flux:input name="interval_km" inputmode="numeric" class:input="font-mono" x-model="draft.interval_km" x-bind="field('interval_km')" />
                                        <flux:description>Rellena uno o los dos: vence lo que llegue antes.</flux:description>
                                        <p id="interval_km-error" class="flex items-start gap-1.5" x-show="hasError('interval_km')" x-cloak>
                                            <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('interval_km')"></span>
                                        </p>
                                    </flux:field>

                                    <p id="interval-error" role="alert" class="flex items-start gap-1.5" x-show="crossError" x-cloak>
                                        <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="crossError"></span>
                                    </p>
                                </div>

                                <fieldset class="grid gap-2">
                                    <legend class="mb-2">Icono</legend>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($iconOptions as $icon => $label)
                                            <label class="catalog-icon" title="{{ $label }}">
                                                <input type="radio" name="icon" value="{{ $icon }}" class="sr-only" x-model="draft.icon">
                                                <flux:icon :icon="$icon" class="size-6" />
                                                <span class="sr-only">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </fieldset>

                                <div class="flex flex-wrap gap-2">
                                    <flux:button type="submit" variant="primary" :loading="false">Guardar</flux:button>
                                    <flux:button type="button" variant="ghost" :loading="false" x-show="editing || !wide" x-on:click="cancel()">Cancelar</flux:button>
                                </div>
                            </form>
                        </template>
                    </x-panel>
                </div>
            </section>

            {{-- ═══ Pestaña: logros ═══ --}}
            <section id="panel-achievements" role="tabpanel" aria-labelledby="tab-achievements" class="pt-6"
                     x-show="isActive('achievements')" x-cloak x-transition.opacity.duration.200ms
                     x-data="catalogPanel({ kind: 'achievement', items: @js($achievementItems), blank: @js(['achievement_name' => '', 'achievement_description' => '', 'rule' => array_key_first($rules), 'rule_value' => '']), needsValue: @js($rulesWithNumber), saved: 'Logro guardado.' })">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem] lg:items-start">
                    <div>
                        <div class="mb-4 lg:hidden">
                            <flux:button size="sm" icon="plus" :loading="false" x-on:click="startNew(true)">Nuevo logro</flux:button>
                        </div>

                        <table class="ledger catalog-ledger catalog-ledger--rules">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Se gana cuando</th>
                                    <th><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($achievements as $achievement)
                                    <tr x-show="!isRemoved('achievement:{{ $achievement['key'] }}')" x-transition.opacity.duration.200ms>
                                        <td class="catalog__name">
                                            <span class="flex items-center gap-3">
                                                <flux:icon :icon="$achievement['icon']" class="size-5 shrink-0 text-graphite" />
                                                {{ $mono($achievement['name']) }}
                                            </span>
                                        </td>
                                        <td class="catalog__rule">{{ $ruleLabel($achievement['rule_label'], $achievement['value']) }}</td>
                                        <td class="catalog__actions">
                                            <div>
                                                <flux:button size="sm" variant="ghost" icon="pencil" :loading="false" aria-label="Editar {{ $achievement['name'] }}"
                                                             x-on:click="startEdit('{{ $achievement['key'] }}')">Editar</flux:button>
                                                <flux:button size="sm" variant="ghost" icon="trash" :loading="false" aria-label="Borrar {{ $achievement['name'] }}"
                                                             x-on:click="askDelete('achievement:{{ $achievement['key'] }}')">Borrar</flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <x-panel variant="raised" class="scroll-mt-4" x-ref="formPanel" x-show="formOpen || wide" x-collapse.duration.200ms x-cloak>
                        <h2 class="mb-4 font-display text-2xl" x-text="editing ? 'Editar logro' : 'Nuevo logro'">Nuevo logro</h2>

                        <template x-for="id in [session]" :key="id">
                            <form method="POST" action="#" novalidate class="grid gap-5"
                                  x-data="validatedForm({ achievement_name: ['required', 'max:60'], achievement_description: ['required', 'max:120'], rule: ['required'], rule_value: ['min:1', 'max:120'] }, @js($errors->messages()))"
                                  x-on:submit="submit($event); finish($event)">
                                <flux:field>
                                    <flux:label>Nombre</flux:label>
                                    <flux:input name="achievement_name" autocomplete="off" x-model="draft.achievement_name" x-bind="field('achievement_name')" />
                                    <p id="achievement_name-error" class="flex items-start gap-1.5" x-show="hasError('achievement_name')" x-cloak>
                                        <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('achievement_name')"></span>
                                    </p>
                                </flux:field>

                                <flux:field>
                                    <flux:label>Descripción</flux:label>
                                    <flux:input name="achievement_description" autocomplete="off" x-model="draft.achievement_description" x-bind="field('achievement_description')" />
                                    <p id="achievement_description-error" class="flex items-start gap-1.5" x-show="hasError('achievement_description')" x-cloak>
                                        <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('achievement_description')"></span>
                                    </p>
                                </flux:field>

                                <flux:field>
                                    <flux:label>Se gana cuando…</flux:label>
                                    <flux:select name="rule" x-model="draft.rule" x-bind="field('rule')">
                                        @foreach ($rules as $rule => $label)
                                            <flux:select.option value="{{ $rule }}">{{ $label }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <p id="rule-error" class="flex items-start gap-1.5" x-show="hasError('rule')" x-cloak>
                                        <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('rule')"></span>
                                    </p>
                                </flux:field>

                                {{-- El número N solo se pide si la regla elegida lo usa («Llega a N rituales»). --}}
                                <div x-show="needsNumber()" x-effect="if (!needsNumber()) draft.rule_value = ''" x-on:input="crossError = ''" x-cloak>
                                    <flux:field>
                                        <flux:label>Valor de N</flux:label>
                                        <flux:input type="number" name="rule_value" min="1" inputmode="numeric" class:input="font-mono" x-model="draft.rule_value" x-bind="field('rule_value')" />
                                        <p id="rule_value-error" class="flex items-start gap-1.5" x-show="hasError('rule_value') || crossError" x-cloak>
                                            <flux:icon.triangle-alert variant="micro" class="mt-1.5 shrink-0" /><span x-text="error('rule_value') || crossError"></span>
                                        </p>
                                    </flux:field>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <flux:button type="submit" variant="primary" :loading="false">Guardar</flux:button>
                                    <flux:button type="button" variant="ghost" :loading="false" x-show="editing || !wide" x-on:click="cancel()">Cancelar</flux:button>
                                </div>
                            </form>
                        </template>
                    </x-panel>
                </div>
            </section>
        </div>

        {{-- Confirmación de borrado (modal de Flux: Esc, clic fuera y «Cancelar» lo cierran). --}}
        <flux:modal name="delete-catalog-item" class="max-w-md">
            <div class="grid gap-4">
                <flux:heading size="lg"><span x-text="'¿Borrar «' + target.name + '»?'"></span></flux:heading>
                <p x-show="target.note" x-text="target.note" x-cloak></p>
                <div class="flex justify-end gap-2">
                    <flux:modal.close><flux:button variant="ghost">Cancelar</flux:button></flux:modal.close>
                    <flux:button variant="danger" :loading="false" x-on:click="confirmDelete()">Borrar</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</x-layouts::prototype>
