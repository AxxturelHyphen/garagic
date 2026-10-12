{{-- Selector claro / oscuro / sistema. Flux guarda la elección en localStorage ($flux.appearance); los colores cambian solos porque son variables CSS. --}}
<flux:radio.group x-data variant="segmented" size="sm" x-model="$flux.appearance" aria-label="Apariencia" {{ $attributes }}>
    <flux:radio value="light" icon="sun">Claro</flux:radio>
    <flux:radio value="dark" icon="moon">Oscuro</flux:radio>
    <flux:radio value="system" icon="computer-desktop">Sistema</flux:radio>
</flux:radio.group>
