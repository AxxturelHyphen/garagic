{{--
    Panel del usuario: la mano de cartas (una gran carta por vehículo, pegadas) y, debajo, solo lo
    imprescindible del vehículo que está al frente: su vitalidad, sus avisos y el acceso a su ficha.
    Lo usan dashboard.blade.php (la pantalla real) y prototype/deck.blade.php (la del prototipo), así lo
    que se verifica sin login es exactamente lo que verá el usuario.

    Los datos entran por props con la forma de _fake-data.php. Cuando existan los modelos Eloquent
    llegarán colecciones con las mismas claves y este componente no cambia.

    PROPS
      vehicles       lista de vehículos (id, name, vitality, card, ...)
      warnings       avisos (vehicle_id, task, status, due_date, due_km, label)
      taskTypes      tipos de tarea por clave (name, icon)
      achievements   logros (key, name, unlocked, earned_with, fresh, tilt): los ganados se estampan en su carta
      emblems        emblema de grabado de cada logro, por clave
      focus          id del vehículo que empieza al frente (null = el primero)
--}}
@props([
    'vehicles',
    'warnings',
    'taskTypes',
    'achievements' => [],
    'emblems' => [],
    'focus' => null,
])
@php
    // Los enlaces dependen de que la ruta exista: el prototipo ya las tiene; la app real, aún no.
    // Sin ruta no se pinta el botón (mejor que un enlace roto).
    $link = fn (string $name, mixed $params = []): ?string => \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : null;

    $list = array_values($vehicles);
    $count = count($list);
    $active = (int) (array_search($focus, array_column($list, 'id'), true) ?: 0);

    // Un sello de logro por carta: el primero que se ganó con ese vehículo.
    $stampFor = function (array $vehicle) use ($achievements, $emblems): ?array {
        foreach ($achievements as $achievement) {
            if ($achievement['unlocked'] && ($achievement['earned_with'] ?? null) === $vehicle['id']) {
                return [
                    'name' => $achievement['name'],
                    'emblem' => $emblems[$achievement['key']] ?? null,
                    'icon' => $achievement['icon'],
                    'tilt' => $achievement['tilt'],
                    'fresh' => $achievement['fresh'],
                ];
            }
        }

        return null;
    };

    $deckCards = array_map(fn (array $v): array => [
        'card' => $v['card'],
        'vitality' => $v['vitality'] ?? 100,
        'empty' => $v['vitality'] === null,
        'label' => $v['name'],
        'stamp' => $stampFor($v),
    ], $list);

    // Solo se enseña lo que pide atención: lo vencido primero y, detrás, lo próximo (máx. 3).
    // sortBy es estable: dentro de cada estado se conserva el orden en que llegan los avisos.
    $pending = fn (array $v) => collect($warnings)
        ->where('vehicle_id', $v['id'])
        ->whereIn('status', ['overdue', 'soon'])
        ->sortBy(fn (array $w): int => $w['status'] === 'overdue' ? 0 : 1)
        ->take(3);
@endphp
<div {{ $attributes }}>
    <x-page-header title="Baraja">
        @if ($createUrl = $link('prototype.vehicles.create'))
            <x-slot:actions>
                <flux:button variant="primary" icon="plus" :href="$createUrl" wire:navigate>Añadir vehículo</flux:button>
            </x-slot:actions>
        @endif
    </x-page-header>

    @if ($count === 0)
        <x-empty-state title="Tu baraja está vacía">
            <x-slot:actions>
                @if ($createUrl)
                    <flux:button variant="primary" icon="plus" :href="$createUrl" wire:navigate>Añadir vehículo</flux:button>
                @endif
            </x-slot:actions>
        </x-empty-state>
    @else
        <x-tarot-deck :cards="$deckCards" :active="$active">
            <x-slot:aside>
                {{-- Todos los paneles ocupan la misma celda: al cambiar de carta uno se desvanece mientras
                     entra el otro, sin que la página salte (con display normal se apilarían 200 ms). --}}
                <div class="garage-aside">
                    @foreach ($list as $i => $v)
                        @php
                            $rows = $pending($v);
                            $showUrl = $link('prototype.vehicles.show', $v['id']);
                            $ritualsUrl = $link('prototype.rituals');
                        @endphp
                        <section class="garage-panel"
                                 role="region" aria-label="{{ $v['name'] }}"
                                 x-show="isActive({{ $i }})"
                                 @if ($i !== $active) x-cloak @endif
                                 x-transition.opacity.duration.200ms>
                            <div class="grid content-start gap-5">
                                <x-vitality-bar :value="$v['vitality']" />

                                <div class="flex flex-wrap gap-3">
                                    @if ($showUrl)
                                        <flux:button variant="primary" :href="$showUrl" wire:navigate>Abrir carta</flux:button>
                                    @endif
                                    @if ($ritualsUrl)
                                        <flux:button variant="outline" :href="$ritualsUrl" wire:navigate>Registrar ritual</flux:button>
                                    @endif
                                </div>
                            </div>

                            <div class="garage-rows">
                                @if ($v['vitality'] === null)
                                    <ul role="list" class="border-t border-hairline">
                                        <x-ritual-row status="none" task="Sin rituales registrados" />
                                    </ul>
                                @elseif ($rows->isEmpty())
                                    <x-status-tag tone="calm">Todo al día</x-status-tag>
                                @else
                                    <ul role="list" class="border-t border-hairline">
                                        @foreach ($rows as $w)
                                            <x-ritual-row :status="$w['status']"
                                                          :task="$taskTypes[$w['task']]['name']"
                                                          :icon="$taskTypes[$w['task']]['icon']"
                                                          :label="$w['label']"
                                                          :date="$w['due_date']"
                                                          :km="$w['due_km']" />
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </section>
                    @endforeach
                </div>
            </x-slot:aside>
        </x-tarot-deck>
    @endif
</div>
