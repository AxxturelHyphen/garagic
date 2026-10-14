{{-- Baraja del prototipo: el mismo <x-garage-board> que la pantalla real (dashboard.blade.php), con los datos de la ruta. --}}
<x-layouts::prototype title="Baraja" :user="$user">
    <x-garage-board
        :vehicles="$vehicles"
        :warnings="$warnings"
        :task-types="$taskTypes"
        :achievements="$achievements"
        :emblems="$emblems"
        :focus="$deckFocus" />
</x-layouts::prototype>
