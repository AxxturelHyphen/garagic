<?php

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/*
 * ORÁCULO SIMULADO del prototipo visual.
 *
 * Por qué se simula: este paquete solo valida el aspecto y el flujo (estados vacío /
 * cargando / respondido y Aceptar / Descartar / Deshacer). No llama a ninguna IA, no toca
 * la base de datos y no usa app/Ai: elige una respuesta de los datos falsos según palabras
 * clave del texto.
 *
 * Qué lo sustituirá: el servicio real de app/Ai/ (cliente compatible con OpenAI + prompts en
 * resources/prompts/). Entonces `pickSample()` y el usleep() de `ask()` desaparecen: la
 * respuesta vendrá de la IA y la espera será la de la red.
 *
 * Por qué la IA no guarda nada sin aceptar: una respuesta de IA puede equivocarse (por eso
 * lleva el sello «IA» y un aviso). Lo único que la IA hace es PROPONER entradas; solo cuando
 * la persona pulsa «Aceptar» se crearía el registro. Aquí `decide()` solo apunta la decisión.
 * Y la app funciona entera sin el Oráculo: nada de aquí es requisito de avisos ni vitalidad.
 */
new #[Layout('layouts::prototype'), Title('Oráculo')] class extends Component
{
    /** Mínimo de caracteres de la consulta: lo usan el servidor y, por @js, el cliente. */
    private const MIN_LENGTH = 10;

    /**
     * Palabras clave (en minúsculas, sin acentos opcionales) que eligen la respuesta de ejemplo.
     * Si ninguna coincide se responde 'start' (urgencia media): ante la duda, ni alarmar ni quitar importancia.
     *
     * @var array<string, array<int, string>>
     */
    private const KEYWORDS = [
        'brakes' => ['fren', 'chirri', 'pedal', 'disco', 'pastilla'],
        'start' => ['arranc', 'cala', 'bater', 'bujía', 'bujia', 'frío', 'frio'],
    ];

    /** 'symptom' (describir un síntoma) o 'invoice' (pegar una factura). */
    public string $mode = 'symptom';

    public int $vehicleId = 3;

    public string $question = '';

    /** Clave de la respuesta de ejemplo elegida; null mientras no hay respuesta. */
    public ?string $answerKey = null;

    /** @var array<int, string> Decisión por entrada propuesta: pending | accepted | discarded */
    public array $decisions = [];

    /**
     * Datos falsos del prototipo. Con Eloquent y app/Ai este método desaparece.
     *
     * @return array<string, mixed>
     */
    #[Computed]
    public function fake(): array
    {
        return require resource_path('views/prototype/_fake-data.php');
    }

    /**
     * Respuesta de ejemplo que se está mostrando (null si aún no hay).
     *
     * @return array<string, mixed>|null
     */
    #[Computed]
    public function sample(): ?array
    {
        return $this->answerKey ? $this->fake['oracle']['samples'][$this->answerKey] : null;
    }

    /**
     * Mensajes de validación en español. Se leen aquí UNA vez y se usan en el servidor
     * (`validate()`) y en el navegador (Alpine, `oracle-form.js`), para que digan lo mismo.
     *
     * @return array<string, string>
     */
    #[Computed]
    public function questionMessages(): array
    {
        return [
            'required' => 'Escribe el síntoma o pega la factura.',
            'min' => 'Cuéntalo con más detalle: mínimo '.self::MIN_LENGTH.' caracteres.',
        ];
    }

    /**
     * Consulta al Oráculo. Valida SIEMPRE en el servidor (el navegador se puede saltar) y
     * simula la espera de la IA para poder ver el estado «Consultando…».
     */
    public function ask(): void
    {
        $this->question = trim($this->question);

        $this->validate(
            [
                'mode' => ['required', Rule::in(['symptom', 'invoice'])],
                'vehicleId' => ['required', Rule::in(array_keys($this->fake['vehicles']))],
                'question' => ['required', 'string', 'min:'.self::MIN_LENGTH],
            ],
            [
                'vehicleId.in' => 'Elige un vehículo de la lista.',
                'question.required' => $this->questionMessages['required'],
                'question.min' => $this->questionMessages['min'],
            ],
        );

        usleep(1_200_000); // Latencia falsa: la IA real tarda segundos y la interfaz debe aguantarlo.

        $this->answerKey = $this->pickSample();
        $this->decisions = array_fill(0, count($this->sample['proposals']), 'pending');
    }

    /**
     * Rellena el formulario con una consulta de ejemplo (no la envía).
     */
    public function useExample(string $key): void
    {
        $example = $this->fake['oracle']['samples'][$key] ?? null;

        // La clave llega del navegador: se ignora si no es una de las nuestras.
        if ($example === null) {
            return;
        }

        $this->mode = $example['mode'];
        $this->vehicleId = $example['vehicle_id'];
        $this->question = $example['question'];
        $this->resetValidation();
    }

    /**
     * Apunta lo que decide la persona sobre una entrada propuesta. 'pending' es «Deshacer».
     * En la app real, 'accepted' crearía el aviso o ritual y 'pending' lo borraría; la IA
     * nunca llega a guardar nada por su cuenta.
     */
    public function decide(int $index, string $decision): void
    {
        $isKnownDecision = in_array($decision, ['pending', 'accepted', 'discarded'], true);

        if (! $isKnownDecision || ! array_key_exists($index, $this->decisions)) {
            return;
        }

        $this->decisions[$index] = $decision;
    }

    /**
     * Vuelve al formulario vacío (conserva modo y vehículo: lo normal es seguir con el mismo).
     */
    public function startOver(): void
    {
        $this->reset('question', 'answerKey', 'decisions');
        $this->resetValidation();
    }

    /**
     * Elige la respuesta de ejemplo: una factura siempre da 'invoice'; un síntoma, la primera
     * lista de palabras clave que aparezca en el texto.
     */
    private function pickSample(): string
    {
        if ($this->mode === 'invoice') {
            return 'invoice';
        }

        $text = mb_strtolower($this->question);

        foreach (self::KEYWORDS as $key => $words) {
            foreach ($words as $word) {
                if (str_contains($text, $word)) {
                    return $key;
                }
            }
        }

        return 'start';
    }
}; ?>

@php
    $urgencyTone = ['low' => 'calm', 'medium' => 'medium', 'high' => 'strong'];
    $urgencyLabel = ['low' => 'Urgencia baja', 'medium' => 'Urgencia media', 'high' => 'Urgencia alta'];
    $vehicles = $this->fake['vehicles'];
    $sample = $this->sample;
@endphp

<div class="oracle">
    <x-page-header title="Oráculo" />

    <p class="oracle__note">Garagic funciona igual sin el Oráculo: los avisos y la vitalidad no dependen de la IA.</p>

    {{-- ── Estado vacío: el formulario. Se oculta al responder (la consulta queda en el arcano). ── --}}
    @unless ($answerKey)
        <div class="oracle__ask">
            <x-panel variant="raised">
                <form wire:submit="ask" class="grid gap-5"
                      x-data="oracleForm({ min: 10, messages: @js($this->questionMessages), copy: @js([
                          'symptom' => ['label' => '¿Qué le pasa?', 'placeholder' => 'Por ejemplo: al frenar se oye un chirrido metálico.'],
                          'invoice' => ['label' => 'Texto de la factura', 'placeholder' => 'Pega aquí el texto de la factura.'],
                      ]) })"
                      x-on:submit.capture="submit($event)">
                    {{-- wire:model sin .live: el modo viaja al servidor con la consulta; lo visual lo cambia Alpine al instante. --}}
                    <flux:radio.group wire:model="mode" variant="segmented" size="sm" aria-label="Tipo de consulta">
                        <flux:radio value="symptom" label="Síntoma" />
                        <flux:radio value="invoice" label="Factura" />
                    </flux:radio.group>

                    <flux:select wire:model="vehicleId" label="Vehículo">
                        @foreach ($vehicles as $vehicle)
                            <flux:select.option value="{{ $vehicle['id'] }}" wire:key="vehicle-{{ $vehicle['id'] }}">{{ $vehicle['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:field>
                        <flux:label x-text="label">{{ $mode === 'invoice' ? 'Texto de la factura' : '¿Qué le pasa?' }}</flux:label>
                        <flux:textarea wire:model="question" rows="6"
                                       x-bind:placeholder="placeholder"
                                       x-bind:class="isInvoice && 'font-mono'"
                                       x-bind:rows="isInvoice ? 9 : 5"
                                       x-bind:aria-invalid="hasError ? 'true' : null"
                                       x-bind:aria-describedby="hasError ? 'question-error' : null"
                                       x-on:blur="leave()" />

                        <div class="flex flex-wrap items-start justify-between gap-x-4 gap-y-1">
                            {{-- Error del navegador (Alpine). El del servidor sale debajo si el navegador se lo saltó. --}}
                            <p id="question-error" role="alert" class="oracle__error" x-show="hasError" x-text="error" x-cloak></p>
                            <flux:error name="question" x-show="! hasError" />

                            <p class="oracle__count ms-auto">
                                <span x-text="count">{{ mb_strlen(trim($question)) }}</span>&nbsp;/&nbsp;<span>10</span>
                            </p>
                        </div>
                    </flux:field>

                    <flux:error name="vehicleId" />

                    <div class="oracle__examples">
                        <span class="text-graphite">Ejemplos</span>
                        <flux:button type="button" size="sm" variant="filled" :loading="false" wire:click="useExample('brakes')">Chirrido al frenar</flux:button>
                        <flux:button type="button" size="sm" variant="filled" :loading="false" wire:click="useExample('start')">Cuesta arrancar en frío</flux:button>
                        <flux:button type="button" size="sm" variant="filled" :loading="false" wire:click="useExample('invoice')">Factura de aceite</flux:button>
                    </div>

                    <div>
                        <flux:button type="submit" variant="primary" :loading="false" wire:loading.attr="disabled" wire:target="ask">Consultar</flux:button>
                    </div>
                </form>
            </x-panel>

            {{-- Grabado decorativo, solo en pantallas anchas y solo en el estado vacío. --}}
            <x-engraving name="engine-otto-1911" class="oracle__engraving text-graphite" />
        </div>
    @endunless

    {{-- ── Cargando: carta boca abajo mientras «responde» (~1,2 s). role=status avisa a lectores de pantalla. ── --}}
    <div role="status" class="oracle__wait-region">
        {{-- Precarga del dorso: mientras el bloque está oculto (display:none) el navegador no pide la imagen de la máscara y la carta saldría en blanco (crossorigin: las máscaras CSS se piden en modo CORS; sin él la caché no reutilizaría esta copia). --}}
        <img src="{{ asset('img/ornament/card-back.webp') }}" alt="" width="1" height="1" crossorigin="anonymous" class="hidden" aria-hidden="true">
        <div wire:loading.flex wire:target="ask" class="oracle__wait hidden">
            <div class="w-28 shrink-0" aria-hidden="true">
                <x-tarot-card :card="['numeral' => '0', 'title' => 'Oráculo']" flipped :flippable="false" :tilt="false" />
            </div>
            <p class="font-display text-2xl">Consultando al Oráculo…</p>
        </div>
    </div>

    {{-- ── Respondido: la respuesta «saca carta» (arcano según la urgencia). ── --}}
    @if ($sample)
        <div class="mt-10">
            <x-arcana-card wire:key="answer-{{ $answerKey }}"
                           :urgency="$sample['urgency']"
                           :summary="$sample['summary']"
                           :question="$sample['mode'] === 'invoice'
                               ? 'Factura del '.$vehicles[$vehicleId]['name']
                               : $vehicles[$vehicleId]['name'].': '.$question">
                @if ($sample['causes'])
                    <section aria-labelledby="oracle-causes">
                        <h3 id="oracle-causes" class="oracle__subtitle">Causas probables</h3>
                        {{-- Única numeración legítima de la pantalla: es una SECUENCIA (de más a menos probable). --}}
                        <ol class="oracle__causes">
                            @foreach ($sample['causes'] as $cause)
                                <li wire:key="cause-{{ $answerKey }}-{{ $loop->index }}">
                                    <p>{{ $cause['title'] }} <span class="oracle__likelihood">{{ $cause['likelihood'] }}</span></p>
                                    <p class="text-graphite">{{ $cause['detail'] }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                <x-slot:advice>
                    <section aria-labelledby="oracle-advice">
                        <h3 id="oracle-advice" class="oracle__subtitle">Qué hacer</h3>
                        <p class="max-w-[65ch]">{{ $sample['advice'] }}</p>
                    </section>
                </x-slot:advice>

                <x-slot:proposals>
                    <section aria-labelledby="oracle-proposals">
                        <h3 id="oracle-proposals" class="oracle__subtitle flex items-center gap-3">
                            <x-ai-seal size="sm" />Entradas propuestas
                        </h3>
                        <p class="mt-1 text-graphite">Nada se guarda hasta que lo aceptes.</p>

                        <ul role="list" class="oracle__proposals">
                            @foreach ($sample['proposals'] as $i => $proposal)
                                @php $decision = $decisions[$i] ?? 'pending'; @endphp
                                <li class="oracle-proposal" data-decision="{{ $decision }}" wire:key="proposal-{{ $answerKey }}-{{ $i }}">
                                    <flux:icon :icon="$this->fake['taskTypes'][$proposal['task']]['icon']" class="oracle-proposal__icon size-5 text-graphite" />

                                    <div class="oracle-proposal__body">
                                        <p class="oracle-proposal__title">{{ $proposal['title'] }}</p>
                                        <p class="oracle-proposal__meta">
                                            <span class="text-graphite">{{ $proposal['kind'] === 'ritual' ? 'Ritual' : 'Aviso' }}</span>
                                            @if ($proposal['date'])<x-date :value="$proposal['date']" />@endif
                                            @if ($proposal['km'])<x-km :value="$proposal['km']" />@endif
                                            @if ($proposal['cost'] !== null)<x-money :amount="$proposal['cost']" />@endif
                                            <span class="text-graphite">{{ $proposal['detail'] }}</span>
                                        </p>
                                    </div>

                                    <div class="oracle-proposal__actions">
                                        @if ($decision === 'pending')
                                            <flux:button size="sm" variant="primary" :loading="false" wire:click="decide({{ $i }}, 'accepted')">Aceptar</flux:button>
                                            <flux:button size="sm" variant="outline" :loading="false" wire:click="decide({{ $i }}, 'discarded')">Descartar</flux:button>
                                        @else
                                            <x-status-tag :tone="$decision === 'accepted' ? 'done' : 'empty'">{{ $decision === 'accepted' ? 'Aceptada' : 'Descartada' }}</x-status-tag>
                                            <flux:button size="sm" variant="ghost" :loading="false" wire:click="decide({{ $i }}, 'pending')">Deshacer</flux:button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </x-slot:proposals>
            </x-arcana-card>

            <div class="mt-6">
                <flux:button variant="primary" :loading="false" wire:click="startOver">Nueva consulta</flux:button>
            </div>
        </div>
    @endif

    {{-- ── Historial: las consultas pasadas (en la app real saldrán de la tabla de consultas). ── --}}
    <section class="mt-14" aria-labelledby="oracle-history">
        <h2 id="oracle-history" class="font-display text-2xl">Consultas anteriores</h2>

        <ul role="list" class="oracle__history">
            @foreach ($this->fake['oracle']['history'] as $entry)
                <li class="oracle-past" wire:key="past-{{ $loop->index }}">
                    <x-date :value="$entry['date']" />
                    <div class="min-w-0">
                        <p>{{ $entry['question'] }}</p>
                        <p class="text-graphite">{{ $vehicles[$entry['vehicle_id']]['name'] }}</p>
                    </div>
                    <x-status-tag :tone="$urgencyTone[$entry['urgency']]">{{ $urgencyLabel[$entry['urgency']] }}</x-status-tag>
                </li>
            @endforeach
        </ul>
    </section>
</div>
