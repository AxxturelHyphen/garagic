/**
 * Contadores de la lista de rituales (pestañas con su número, secciones que se ocultan
 * cuando se quedan vacías, mensaje de "lista vacía").
 *
 * POR QUÉ ES UN "MIXIN" Y NO UN COMPONENTE ENTERO
 * Quién se ve y quién no ya lo decide `listFilter` (ui.js): guarda los filtros activos
 * (estado y vehículo) y responde a `matches(fila)`. Lo único que le falta es CONTAR, y eso
 * necesita conocer todas las filas. Para no duplicar `listFilter`, este módulo solo aporta
 * los métodos de contar y se mezcla con él en el x-data de la página:
 *
 *   x-data="{ ...listFilter({ status: 'all', vehicle: 'all' }), ...ritualCounts(@js($rows)) }"
 *
 * Al mezclarlos, `this` es el objeto unido, así que `this.matches` y `this.filters`
 * (de listFilter) están disponibles aquí. Por eso no hay getters: se copiarían como valor.
 *
 * Cada fila es { status: 'overdue' | 'soon' | 'ok' | 'done', vehicle: id }.
 * Todo ocurre en el navegador: las filas ya están en la página, no hay petición.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('ritualCounts', (rows = []) => ({
        rows,

        /**
         * Filas que hay en una pestaña, respetando solo el filtro de vehículo (el número de
         * cada pestaña no depende de la pestaña activa, sino del vehículo elegido).
         */
        count(status) {
            const wanted = this.filters.vehicle;

            return this.rows.filter(
                (row) => (wanted === 'all' || String(row.vehicle) === String(wanted)) && (status === 'all' || row.status === status),
            ).length;
        },

        /** Filas visibles ahora mismo cuyo estado está en la lista (para ocultar secciones vacías). */
        visibleIn(statuses) {
            return this.rows.filter((row) => statuses.includes(row.status) && this.matches(row)).length;
        },

        /** Ninguna fila pasa los filtros. */
        isEmpty() {
            return this.rows.every((row) => !this.matches(row));
        },
    }));
});
