/**
 * Formulario de vehículo: la carta de al lado se escribe mientras se rellena.
 *
 * POR QUÉ ES UN COMPONENTE APARTE DE `validatedForm` (forms.js)
 * `validatedForm` solo sabe de reglas y mensajes de error. Esto es otra cosa: mantener la
 * vista previa de la carta al día. Van anidados: este `x-data` envuelve toda la pantalla
 * (formulario + carta) y `validatedForm` va dentro, en el <form>. Alpine deja que lo de
 * dentro lea lo de fuera, así los campos se enlazan con `x-model` a las variables de aquí.
 *
 * TODO OCURRE EN EL NAVEGADOR. El servidor sigue teniendo la última palabra: si el
 * formulario es válido, el envío sigue su camino y Laravel vuelve a validar.
 *
 *   <div x-data="vehicleForm({ ... })">
 *       <form x-data="validatedForm(reglas, errores)" x-on:submit="submit($event); preventFakeSend($event)">
 *           <flux:input name="plate" x-model="plate" />
 *       </form>
 *       <x-tarot-card data-vehicle-preview ... />
 *   </div>
 */
import { toNumber } from './forms.js';

/** Qué grabado lleva cada tipo de vehículo (mismos nombres que public/img/engravings). */
const ENGRAVINGS = {
    car: { name: 'car-libbey-1900', ratio: '900 / 592' },
    motorbike: { name: 'moto-werner-1898', ratio: '657 / 387' },
    van: { name: 'van-montagu-1928', ratio: '719 / 333' },
};

/** 214800 -> "214.800" (el punto separa miles en España; toLocaleString no agrupa los de 4 cifras). */
function thousands(number) {
    return String(number).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.addEventListener('alpine:init', () => {
    Alpine.data('vehicleForm', ({ type = 'car', plate = '', brand = '', model = '', km = '', engravingBase = '' } = {}) => ({
        type,
        plate,
        brand,
        model,
        km,
        saved: false,

        init() {
            // Alpine.effect vuelve a ejecutar paint() cada vez que cambia CUALQUIER variable
            // que paint() lee: no hay que enumerar qué campo cambia qué parte de la carta.
            Alpine.effect(() => this.paint());
        },

        get title() {
            return `${this.brand} ${this.model}`.trim() || 'Tu vehículo';
        },

        get plateText() {
            return this.plate.trim().toUpperCase() || '0000 XXX';
        },

        /**
         * Escribe en la carta lo que hay en el formulario. La carta es HTML ya pintado por Blade
         * (<x-tarot-card data-vehicle-preview>), así que se cambia su texto directamente con
         * textContent, nunca innerHTML: lo que escribe el usuario no se interpreta como HTML.
         */
        paint() {
            const card = this.$root.querySelector('[data-vehicle-preview]');
            if (!card) {
                return;
            }

            card.querySelector('.tc__title').textContent = `${this.title}.`;
            card.querySelector('.tc__plate').textContent = this.plateText;

            // Dorso: los dos primeros datos son los kilómetros y la matrícula.
            const [kmCell, plateCell] = card.querySelectorAll('.tc__cartouche dd');
            const kmNumber = toNumber(this.km === '' ? '0' : this.km, 'text');
            kmCell.textContent = `${thousands(kmNumber ?? 0)} km`;
            plateCell.textContent = this.plateText;

            // El tipo cambia el grabado. Si la carta lleva una foto (el Seat Ibiza), se respeta la foto.
            const art = card.querySelector('.tc__art');
            const engraving = ENGRAVINGS[this.type];
            if (art && engraving) {
                art.style.setProperty('--engraving', `url('${engravingBase}/${engraving.name}.webp')`);
                art.style.aspectRatio = engraving.ratio;
            }
        },

        /**
         * SOLO PROTOTIPO. No hay ruta POST: si el formulario es válido, se frena el envío y se
         * avisa de que "se ha guardado". Con la ruta real se quita esta llamada y el envío
         * válido llega a Laravel, que valida otra vez y guarda.
         * `submit()` de validatedForm ya ha llamado a preventDefault() si algo no cumplía las
         * reglas; por eso `defaultPrevented` significa "inválido".
         */
        preventFakeSend(event) {
            if (event.defaultPrevented) {
                this.saved = false;
                return;
            }
            event.preventDefault();
            this.saved = true;
        },
    }));
});
