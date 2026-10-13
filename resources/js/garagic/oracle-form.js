/**
 * Formulario del Oráculo (prototipo): validación y contador en el navegador.
 *
 * POR QUÉ EXISTE
 * Avisar «te falta texto» al instante, sin esperar al servidor. Es solo comodidad: el
 * SERVIDOR (Livewire, `$this->validate()` en `ask()`) vuelve a validar siempre y tiene la
 * última palabra, porque el navegador se puede saltar.
 *
 * Misma idea que `validatedForm` (forms.js), pero más corta porque aquí hay UN solo campo
 * y su estado ya vive en Livewire: leemos `$wire.question` (la propiedad del componente)
 * en vez de leer el DOM. Así el contador también se entera cuando el servidor cambia el
 * texto (los botones de ejemplo) sin que nadie haya escrito.
 *
 * Los mensajes y el mínimo llegan desde PHP (`@js(...)`), los MISMOS que usa el servidor:
 * una sola fuente de verdad, así cliente y servidor nunca se contradicen.
 *
 *   <form wire:submit="ask"
 *         x-data="oracleForm({ min: 10, messages: {...}, copy: {...} })"
 *         x-on:submit.capture="submit($event)">
 *
 * `.capture` hace que nuestro control corra ANTES que el `wire:submit` de Livewire (si no,
 * Livewire enviaría la petición antes de que pudiéramos pararla).
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('oracleForm', ({ min, messages, copy }) => ({
        touched: false, // ya intentó enviar o salió del campo con algo escrito

        /** Texto de la consulta, tal como lo ve el servidor (que también lo recorta). */
        get text() {
            return (this.$wire.question ?? '').trim();
        },

        get count() {
            return this.text.length;
        },

        get isInvoice() {
            return this.$wire.mode === 'invoice';
        },

        /** Etiqueta y marcador del campo según el modo (visual: no necesita al servidor). */
        get label() {
            return this.isInvoice ? copy.invoice.label : copy.symptom.label;
        },

        get placeholder() {
            return this.isInvoice ? copy.invoice.placeholder : copy.symptom.placeholder;
        },

        /** Mensaje de error actual, o '' si no toca avisar o el texto es válido. */
        get error() {
            if (!this.touched) {
                return '';
            }
            if (this.count === 0) {
                return messages.required;
            }

            return this.count < min ? messages.min : '';
        },

        get hasError() {
            return this.error !== '';
        },

        /** Avisa al salir del campo, pero no regaña por dejarlo vacío. */
        leave() {
            if (this.count > 0) {
                this.touched = true;
            }
        },

        /**
         * Se llama desde x-on:submit.capture. Si el texto no cumple, se corta el envío
         * (Livewire no hace ninguna petición) y el foco vuelve al campo. Si cumple, no se
         * toca el evento: Livewire envía y el servidor valida otra vez.
         */
        submit(event) {
            this.touched = true;

            if (!this.hasError) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            this.$root.querySelector('textarea')?.focus();
        },
    }));
});
