import { annotate } from 'rough-notation';

/**
 * Subrayado "a mano" bajo el enlace activo de la navegación (efecto libreta de taller).
 *
 * En Blade, el <a> activo lleva `data-rough-underline` (lo pone <x-nav-link>).
 * El trazo usa currentColor (no le pasamos color), así que cambia solo con claro/oscuro.
 * Con "reducir movimiento" se dibuja sin animar.
 *
 * rough-notation inserta un <svg> hermano del enlace con `position: absolute` y se
 * redibuja con un ResizeObserver; por eso <x-nav-link> envuelve el enlace en un
 * <span class="relative inline-flex">.
 *
 * @param {ParentNode} root Zona del documento donde buscar enlaces por subrayar.
 */
export function drawRoughUnderlines(root = document) {
    const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    root.querySelectorAll('[data-rough-underline]:not([data-rough-drawn])').forEach((el) => {
        // Oculto (menú móvil cerrado): no tiene medidas y el trazo saldría descolocado.
        if (el.offsetParent === null) {
            return;
        }

        el.setAttribute('data-rough-drawn', '');

        annotate(el, {
            type: 'underline',
            strokeWidth: 1.5,
            padding: 2,
            iterations: 1,
            animate: !calm,
            animationDuration: 250,
        }).show();
    });
}

// Se dibuja cuando las fuentes ya han cargado: si no, el ancho del enlace cambia después
// y el trazo quedaría más corto o más largo que la palabra.
const drawWhenFontsAreReady = () => document.fonts.ready.then(() => drawRoughUnderlines());

document.addEventListener('DOMContentLoaded', drawWhenFontsAreReady);

// Livewire cambia el contenido sin recargar la página (wire:navigate): hay que volver a dibujar.
document.addEventListener('livewire:navigated', drawWhenFontsAreReady);
