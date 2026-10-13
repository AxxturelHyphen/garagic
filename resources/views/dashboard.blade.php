{{--
    Pantalla real del usuario. Aún no hay modelos Eloquent, así que los datos son los falsos del
    prototipo; cuando existan, el controlador pasará colecciones con las mismas claves y esta
    línea desaparece. El aspecto vive en <x-garage-board>, el mismo que usa /prototipo/baraja.
--}}
@php($fake = require resource_path('views/prototype/_fake-data.php'))
<x-layouts::app title="Baraja">
    <x-garage-board
        :vehicles="$fake['vehicles']"
        :warnings="$fake['warnings']"
        :task-types="$fake['taskTypes']"
        :achievements="$fake['achievements']"
        :emblems="$fake['emblems']"
        :focus="$fake['deckFocus']" />
</x-layouts::app>
