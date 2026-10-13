/**
 * Muro de logros: el sello «recién ganado» se estampa cuando llega a la vista.
 *
 * POR QUÉ NO BASTA CON LA ANIMACIÓN CSS: `.stamp[data-fresh]` ya se estampa solo, pero en
 * cuanto carga la página. Si el sello queda por debajo del pliegue (en móvil es lo normal),
 * la animación se gasta donde nadie la ve. Aquí solo decidimos CUÁNDO empieza:
 *
 *   1. Blade pinta el contenedor con `data-stamp-wait`; achievements.css pone la animación en
 *      pausa mientras exista ese atributo (en su primer fotograma: sello invisible, grande).
 *   2. Alpine observa el contenedor (`x-intersect.once`) y, al verse, quita el atributo:
 *      la animación arranca y dura lo de siempre (250 ms). Una sola vez.
 *
 * Sin JS (`@media (scripting: none)` en el CSS) o con «reducir movimiento» el sello se ve
 * directamente en su estado final: la animación dura 0,01 ms (motion.css).
 *
 *   <li x-data="stampOnView" data-stamp-wait
 *       x-bind:data-stamp-wait="waiting ? '' : null"
 *       x-intersect.once.threshold.60="release()">
 *       <x-achievement-stamp fresh … />
 *   </li>
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('stampOnView', () => ({
        waiting: true,

        release() {
            this.waiting = false;
        },
    }));
});
