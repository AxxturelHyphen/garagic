/**
 * Catálogo del admin (tipos de tarea y logros): todo ocurre en el navegador.
 *
 * Es un PROTOTIPO con datos falsos: borrar y guardar se simulan (la fila desaparece, sale un
 * aviso). En la app real el borrado y el guardado serán acciones de Livewire (`wire:click`,
 * `wire:submit`) y esta parte solo conservará lo visual: abrir el modal, rellenar el formulario.
 *
 * Dos componentes anidados:
 *   catalogAdmin  raíz de la página: qué se va a borrar y el modal de confirmación (uno para
 *                 las dos pestañas, así hay una sola confirmación en todo el catálogo).
 *   catalogPanel  uno por pestaña: la lista + el formulario de alta/edición.
 *
 * La validación de cada campo NO está aquí: la hace `validatedForm` (forms.js). Aquí solo está
 * lo que `validatedForm` no sabe hacer porque mira un campo cada vez: reglas que dependen de
 * DOS campos (ver `crossProblem`).
 */
document.addEventListener('alpine:init', () => {
    /**
     * @param {Object<string, {name: string, note: string, done: string}>} targets
     *        Qué se puede borrar, por identificador ('type:itv'): nombre para el título del modal,
     *        nota de aviso y texto del aviso posterior. Blade lo rellena con @js.
     */
    Alpine.data('catalogAdmin', (targets = {}) => ({
        removed: [], // identificadores borrados (solo en esta sesión del prototipo)
        target: { id: '', name: '', note: '', done: '' },

        askDelete(id) {
            this.target = { id, ...targets[id] };
            this.$flux.modal('delete-catalog-item').show();
        },

        confirmDelete() {
            this.removed.push(this.target.id);
            this.$flux.modal('delete-catalog-item').close();
            this.toast(this.target.done);
        },

        isRemoved(id) {
            return this.removed.includes(id);
        },

        /** Flux escucha el evento `toast-show` (lo mismo que dispara Flux::toast() desde PHP). */
        toast(text) {
            this.$dispatch('toast-show', { duration: 4000, slots: { text }, dataset: {} });
        },
    }));

    /**
     * @param {Object} config
     * @param {'type'|'achievement'} config.kind     qué catálogo gestiona este panel
     * @param {Object} config.items                  elementos por clave, con los nombres de los campos del formulario
     * @param {Object} config.blank                  valores de un formulario en blanco
     * @param {string[]} config.needsValue           reglas de logro que piden un número N (solo kind 'achievement')
     * @param {string} config.saved                  texto del aviso al guardar
     */
    Alpine.data('catalogPanel', ({ kind, items = {}, blank = {}, needsValue = [], saved = 'Guardado.' }) => ({
        editing: null, // clave del elemento que se edita; null = alta
        draft: { ...blank }, // con qué valores se rellena el formulario
        session: 0, // cambia en cada Nuevo/Editar (ver nota en el Blade: recrea el formulario)
        formOpen: false, // en móvil el formulario va plegado hasta que se pide
        wide: false, // en escritorio el formulario está siempre a la vista
        crossError: '', // error de las reglas de dos campos

        init() {
            // `wide` sigue al ancho de la ventana: así la animación de plegado solo se usa en móvil.
            const query = window.matchMedia('(min-width: 64rem)');
            this.wide = query.matches;
            query.addEventListener('change', (event) => {
                this.wide = event.matches;
            });
        },

        startNew(open = false) {
            this.begin(null, { ...blank }, open);
        },

        startEdit(key) {
            this.begin(key, { ...items[key] }, true);
        },

        cancel() {
            this.startNew(false);
        },

        /**
         * Prepara el formulario. `session` es la clave del `x-for` que lo envuelve: al cambiar,
         * Alpine tira el formulario y crea otro con su `validatedForm` limpio. `validatedForm`
         * no tiene un «reiniciar»: así no queda ningún error del elemento anterior.
         */
        begin(key, values, open) {
            this.editing = key;
            this.draft = values;
            this.crossError = '';
            this.session++;
            this.formOpen = open;

            if (open) {
                // En móvil el panel se despliega en 200 ms (x-collapse): hasta que termina, la página
                // es más corta y el scroll no llega al formulario. Se espera a que haya crecido.
                const unfolding = this.wide ? 0 : 250;

                this.$nextTick(() => {
                    this.$refs.formPanel.querySelector('input:not([type="radio"])')?.focus({ preventScroll: true });

                    setTimeout(() => {
                        const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        this.$refs.formPanel.scrollIntoView({ block: this.wide ? 'nearest' : 'start', behavior: calm ? 'auto' : 'smooth' });
                    }, unfolding);
                });
            }
        },

        /** ¿La regla elegida pide un número N? («Llega a N rituales» sí; «Registra su primer ritual» no). */
        needsNumber() {
            return needsValue.includes(this.draft.rule);
        },

        /**
         * Reglas que dependen de dos campos. `validatedForm` valida cada campo por separado y
         * se salta los vacíos (como Laravel), así que «uno de los dos» no puede expresarse allí.
         * Devuelve { field, message } o null. El servidor las volverá a comprobar.
         */
        crossProblem(form) {
            const value = (name) => form.elements.namedItem(name)?.value.trim() ?? '';

            if (kind === 'type' && value('interval_months') === '' && value('interval_km') === '') {
                return { field: 'interval_months', message: 'Rellena los meses, los kilómetros o los dos.' };
            }
            if (kind === 'achievement' && this.needsNumber() && value('rule_value') === '') {
                return { field: 'rule_value', message: 'Escribe cuántos hacen falta.' };
            }

            return null;
        },

        /**
         * Se llama justo DESPUÉS de `submit($event)` de validatedForm. Si este ya cortó el envío
         * (`defaultPrevented`), no hay nada que hacer. Si no, se comprueban las reglas de dos
         * campos y, si todo está bien, el prototipo simula el guardado.
         * En la app real aquí NO habría preventDefault: el formulario se envía y el servidor decide.
         */
        finish(event) {
            if (event.defaultPrevented) {
                return;
            }

            event.preventDefault();

            const problem = this.crossProblem(event.target);
            if (problem) {
                this.crossError = problem.message;
                event.target.elements.namedItem(problem.field)?.focus();
                return;
            }

            this.$dispatch('toast-show', { duration: 4000, slots: { text: saved }, dataset: {} });
            this.cancel();
        },
    }));
});
