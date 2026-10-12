/**
 * Cartas de tarot: giro, inclinación con el cursor y la mano de cartas.
 *
 * Todo ocurre en el navegador (Alpine): no hay datos ni peticiones al servidor. Lo que se
 * ve (giro, inclinación, desgaste) lo hace CSS en resources/css/card.css; aquí solo se
 * guarda el estado mínimo (¿girada?, ¿qué carta está al frente?) y se escribe en el DOM.
 *
 * Se carga solo desde app.js (import.meta.glob), no hay que importarlo a mano.
 *
 *   <x-tarot-card …>   usa  tarotCard(...)   (lo pinta resources/views/components/tarot-card.blade.php)
 *   <x-tarot-deck …>   usa  tarotDeck(...)   (lo pinta resources/views/components/tarot-deck.blade.php)
 */
document.addEventListener('alpine:init', () => {
    /**
     * Una carta: giro e inclinación.
     *
     * Opciones (las escribe Blade):
     *   flipped  ¿empieza girada (dorso a la vista)?
     *   tilt     ¿se inclina con el cursor?
     *   index    posición de la carta en una mano (null si va suelta)
     *   label    nombre de la carta, para el texto del botón
     *
     * Si la carta está dentro de una mano (<x-tarot-deck>) se porta distinto: pulsarla
     * cuando NO está al frente la saca al frente; solo gira cuando ya está al frente.
     */
    Alpine.data('tarotCard', ({ flipped = false, tilt = true, index = null, label = '' } = {}) => ({
        flipped,
        tiltOn: false,

        init() {
            // La inclinación solo con ratón y sin "reducir movimiento": en táctil no hay
            // hover (no tendría sentido) y a quien pide menos movimiento le marearía.
            const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
            const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.tiltOn = tilt && finePointer && !calm;

            // En una mano, al perder el frente la carta vuelve a mostrar su cara.
            const deck = this.deck();
            if (deck) {
                this.$watch(() => deck.isActive(index), (active) => {
                    if (!active) {
                        this.flipped = false;
                        this.tiltReset();
                    }
                });
            }
        },

        /** La mano que contiene esta carta (null si la carta va suelta). */
        deck() {
            if (index === null) {
                return null;
            }
            const root = this.$root.closest('[data-tarot-deck]');
            return root ? Alpine.$data(root) : null;
        },

        flip() {
            const deck = this.deck();
            if (deck && !deck.isActive(index)) {
                deck.pick(index);
                return;
            }
            this.flipped = !this.flipped;
        },

        /** Texto del botón que cubre la carta: dice lo que pasará al pulsarlo. */
        get hitLabel() {
            const deck = this.deck();
            return deck && !deck.isActive(index)
                ? `Sacar al frente: ${label}`
                : `Girar la carta: ${label}`;
        },

        /**
         * aria-pressed solo tiene sentido cuando el botón gira la carta (el estado es
         * "girada"). Cuando el botón solo la saca al frente devolvemos null, y Alpine
         * quita el atributo en vez de poner un "false" engañoso.
         */
        get pressed() {
            const deck = this.deck();
            if (deck && !deck.isActive(index)) {
                return null;
            }
            return this.flipped ? 'true' : 'false';
        },

        tiltTo(event) {
            if (!this.tiltOn) {
                return;
            }
            // En una mano solo se inclina la carta que está al frente.
            const deck = this.deck();
            if (deck && !deck.isActive(index)) {
                return;
            }

            const box = this.$root.getBoundingClientRect();
            const x = (event.clientX - box.left) / box.width - 0.5; // -0,5 (izquierda) … 0,5 (derecha)
            const y = (event.clientY - box.top) / box.height - 0.5;

            // ±5° como mucho: lo justo para que parezca una carta en la mano, no una tablet.
            // Las variables las lee card.css; el brillo (--glare-*) sigue al cursor.
            const style = this.$root.style;
            style.setProperty('--tilt-x', `${(-y * 10).toFixed(2)}deg`);
            style.setProperty('--tilt-y', `${(x * 10).toFixed(2)}deg`);
            style.setProperty('--glare-x', `${Math.round((x + 0.5) * 100)}%`);
            style.setProperty('--glare-y', `${Math.round((y + 0.5) * 100)}%`);
            style.setProperty('--glare-o', '1');
        },

        tiltReset() {
            ['--tilt-x', '--tilt-y', '--glare-o'].forEach((prop) => this.$root.style.removeProperty(prop));
        },
    }));

    /**
     * La mano: qué carta está al frente. La posición de cada una sale de su "profundidad".
     *
     * Opciones: active (índice inicial) y count (nº de cartas).
     * Emite el evento `tarot-deck:change` ({ index }) en cada cambio, para que quien
     * componga la mano (p. ej. <x-garage-board>) pueda reaccionar.
     */
    Alpine.data('tarotDeck', ({ active = 0, count = 1 } = {}) => ({
        active,
        count,

        pick(index) {
            if (index === this.active) {
                return;
            }
            this.active = index;
            this.$dispatch('tarot-deck:change', { index });
        },

        next() {
            this.pick((this.active + 1) % this.count);
        },

        prev() {
            this.pick((this.active - 1 + this.count) % this.count);
        },

        isActive(index) {
            return this.active === index;
        },

        /**
         * 0 = al frente; 1, 2, 3 = detrás, cada vez más lejos. El módulo (%) hace que, al
         * sacar una carta del medio, las que estaban delante pasen al final de la fila
         * (orden circular) en vez de quedarse con un número negativo.
         */
        depth(index) {
            return (index - this.active + this.count) % this.count;
        },

        /** Flechas del teclado: cambian de carta y llevan el foco a la que queda al frente. */
        onKeydown(event) {
            const moves = {
                ArrowRight: () => this.next(),
                ArrowDown: () => this.next(),
                ArrowLeft: () => this.prev(),
                ArrowUp: () => this.prev(),
                Home: () => this.pick(0),
                End: () => this.pick(this.count - 1),
            };

            if (!moves[event.key]) {
                return;
            }

            event.preventDefault(); // que las flechas no desplacen la página
            moves[event.key]();
            this.focusActive();
        },

        focusActive() {
            this.$nextTick(() => {
                this.$root.querySelector(`[data-deck-index="${this.active}"] .tc__hit`)?.focus();
            });
        },
    }));
});
