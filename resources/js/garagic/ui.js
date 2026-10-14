/**
 * Interacciones de interfaz que ocurren solo en el navegador (Alpine).
 *
 * Regla del proyecto: lo que cambia datos o necesita al servidor va en Livewire/PHP;
 * lo que solo cambia lo que se ve (abrir, cerrar, filtrar una lista que ya está en
 * la página) va aquí. Por eso es instantáneo: no hay petición.
 *
 * Todo se registra en 'alpine:init'. Se carga solo desde app.js (import.meta.glob).
 */
document.addEventListener('alpine:init', () => {
    /**
     * Pestañas con teclado (flechas, Inicio y Fin) y roles ARIA.
     *
     *   <div x-data="tabs({ initial: 'tipos' })">
     *       <div role="tablist" x-on:keydown="onKeydown($event)">
     *           <button type="button" role="tab" data-tab="tipos"
     *                   x-bind:aria-selected="isActive('tipos')"
     *                   x-bind:tabindex="isActive('tipos') ? 0 : -1"
     *                   x-on:click="select('tipos')">Tipos</button>
     *       </div>
     *       <section role="tabpanel" x-show="isActive('tipos')" x-transition.opacity.duration.200ms>…</section>
     *   </div>
     *
     * Solo la pestaña activa entra en el orden de tabulador (tabindex 0): con las
     * flechas se mueve entre pestañas, como en cualquier barra de pestañas del sistema.
     */
    Alpine.data('tabs', ({ initial = '' } = {}) => ({
        current: initial,

        select(id) {
            this.current = id;
        },

        isActive(id) {
            return this.current === id;
        },

        onKeydown(event) {
            const keys = ['ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (!keys.includes(event.key)) {
                return;
            }

            const tabs = [...this.$root.querySelectorAll('[role="tab"]')];
            const index = tabs.indexOf(document.activeElement);
            if (index === -1) {
                return;
            }

            event.preventDefault(); // que las flechas no desplacen la página

            // El módulo (%) hace que al pasar del último se vuelva al primero y viceversa.
            let next = index;
            if (event.key === 'ArrowRight') next = (index + 1) % tabs.length;
            if (event.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'Home') next = 0;
            if (event.key === 'End') next = tabs.length - 1;

            tabs[next].focus();
            this.select(tabs[next].dataset.tab);
        },
    }));

    /**
     * Filtros de una lista que YA está en la página (rituales por estado y vehículo).
     * Cada fila declara sus valores y se muestra solo si coincide con todos los filtros.
     *
     *   <div x-data="listFilter({ status: 'all', vehicle: 'all' })">
     *       <button type="button" x-on:click="set('status', 'overdue')" x-bind:aria-pressed="is('status', 'overdue')">Vencidos</button>
     *       <li x-show="matches({ status: 'overdue', vehicle: 3 })">…</li>
     *   </div>
     *
     * El valor 'all' significa "sin filtrar".
     */
    Alpine.data('listFilter', (initial = {}) => ({
        filters: { ...initial },

        set(key, value) {
            this.filters[key] = value;
        },

        is(key, value) {
            return this.filters[key] === value;
        },

        matches(attributes) {
            return Object.entries(this.filters).every(([key, wanted]) => {
                return wanted === 'all' || String(attributes[key]) === String(wanted);
            });
        },

        reset() {
            Object.keys(this.filters).forEach((key) => {
                this.filters[key] = 'all';
            });
        },
    }));

    /**
     * Abrir y cerrar: sirve para un bloque plegable (menú móvil, formulario de ritual)
     * y para un modal sencillo.
     *
     *   <div x-data="disclosure()" x-on:keydown.escape.window="close()">
     *       <button type="button" x-on:click="toggle()" x-bind:aria-expanded="open">Menú</button>
     *       <div x-show="open" x-collapse.duration.200ms x-cloak>…</div>
     *   </div>
     *
     * Para un modal, el panel lleva además `x-trap.noscroll="open"` (Alpine mantiene el
     * foco dentro y bloquea el scroll de la página). Los borrados reales usan <flux:modal>.
     */
    Alpine.data('disclosure', ({ open = false } = {}) => ({
        open,

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },
    }));

    /**
     * Claro/oscuro con un solo botón. Flux ya guarda la preferencia ('light', 'dark' o
     * 'system') en localStorage y pone o quita la clase `dark` en <html>: aquí solo la
     * leemos y la cambiamos. Los colores cambian solos porque son variables CSS.
     */
    Alpine.data('themeToggle', () => ({
        get isDark() {
            return this.$flux.dark;
        },

        toggle() {
            this.$flux.appearance = this.$flux.dark ? 'light' : 'dark';
        },
    }));

    /**
     * Número que sube hasta su valor (vitalidad, recuentos). El HTML ya trae el valor
     * final, así que sin JS o con "reducir movimiento" se ve igual, sin animar.
     *
     *   <span x-data="counter(95)" x-text="text">95</span>
     */
    Alpine.data('counter', (target = 0, { duration = 300 } = {}) => ({
        value: target,

        get text() {
            return this.value.toLocaleString('es-ES');
        },

        init() {
            const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (calm || duration <= 0) {
                return;
            }

            this.value = 0;
            const start = performance.now();

            const step = (now) => {
                const progress = Math.min(1, (now - start) / duration);
                // Frena al final (como la barra de vitalidad): 1 - (1 - t)^3.
                this.value = Math.round(target * (1 - Math.pow(1 - progress, 3)));

                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            };

            requestAnimationFrame(step);
        },
    }));
});
