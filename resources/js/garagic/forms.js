/**
 * Validación de formularios en el navegador: `validatedForm`.
 *
 * POR QUÉ EXISTE
 * Avisar al usuario mientras escribe ("la matrícula no es válida") sin esperar a una
 * petición al servidor. Es solo comodidad: el navegador lo puede saltar (o el usuario
 * desactivar JS), así que el SERVIDOR (Fortify, reglas de Laravel) vuelve a validar
 * siempre y tiene la última palabra. Por eso:
 *   - si el formulario es válido aquí, NO se hace preventDefault: el envío sigue su
 *     camino normal y el servidor decide;
 *   - si el servidor rechaza algo, esos errores entran por `serverErrors` y se pintan
 *     en el mismo sitio que los de cliente.
 *
 * CÓMO SE USA (Blade). El `x-data` va en el <form>; `novalidate` apaga los globos del
 * navegador para que se vean los nuestros; los errores del servidor entran con @js:
 *
 *   <form method="POST" action="{{ route('login.store') }}" novalidate
 *         x-data="validatedForm({ email: ['required', 'email'], password: ['required'] }, @js($errors->messages()))"
 *         x-on:submit="submit($event)">
 *       @csrf
 *       <flux:input name="email" type="email" label="Correo" x-bind="field('email')" />
 *       <p id="email-error" x-show="hasError('email')" x-text="error('email')" x-cloak></p>
 *       …
 *   </form>
 *
 * Si el <form> también lleva wire:submit (Livewire), `x-on:submit` debe ir ANTES en el
 * HTML: así, cuando el formulario es inválido, `submit()` corta la propagación y
 * Livewire no llega a enviar nada.
 *
 * REGLAS (cada una es un texto "nombre" o "nombre:parámetro", como en Laravel):
 *   required        no vacío (en una casilla: marcada)
 *   min:n / max:n   longitud del texto; si el campo es type="number", el valor
 *   email           formato de correo
 *   plate           matrícula española (1234 BCD, o la antigua M-1234-AB)
 *   date            fecha real (AAAA-MM-DD del <input type="date"> o DD/MM/AAAA)
 *   kmMin:n         número (p. ej. 214.800) mayor o igual que n: un coche no "baja" de km
 *   confirmed:otro  igual que el campo `otro` (se pone en el campo de confirmación:
 *                   password_confirmation: ['confirmed:password'])
 * Salvo `required`, una regla se salta si el campo está vacío (como en Laravel): un
 * campo opcional vacío no es un error.
 *
 * ESTADO (lo que lee la plantilla):
 *   errors    { campo: 'mensaje' } lo que se está mostrando
 *   touched   { campo: true } el usuario ya salió del campo (se avisa "tarde", no
 *             mientras escribe la primera vez; una vez avisado, se corrige en vivo)
 *   valid     true si todas las reglas se cumplen ahora mismo
 */

const MODERN_PLATE = /^\d{4}\s?[BCDFGHJKLMNPRSTVWXYZ]{3}$/i; // 1234 BCD (sin vocales, Ñ ni Q)
const OLD_PLATE = /^[A-Z]{1,2}[\s-]?\d{4}[\s-]?[A-Z]{1,2}$/i; // M-1234-AB (provincial)

/**
 * Convierte "214.800", "214800" o "1.234,5" en número. En España el punto separa
 * miles y la coma los decimales; un <input type="number"> ya entrega "214800".
 */
export function toNumber(value, type) {
    const text = String(value).trim();
    const number = type === 'number' ? Number(text) : Number(text.replace(/\./g, '').replace(',', '.'));

    return Number.isNaN(number) ? null : number;
}

/**
 * Lee AAAA-MM-DD o DD/MM/AAAA y devuelve la fecha, o null si no existe en el
 * calendario (31/02 no es una fecha: `new Date` la "corregiría" a marzo, por eso se
 * comprueba que el día y el mes siguen siendo los mismos).
 */
export function parseDate(value) {
    const text = String(value).trim();
    const iso = text.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    const spanish = text.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);

    let year;
    let month;
    let day;

    if (iso) {
        [year, month, day] = [iso[1], iso[2], iso[3]].map(Number);
    } else if (spanish) {
        [day, month, year] = [spanish[1], spanish[2], spanish[3]].map(Number);
    } else {
        return null;
    }

    const date = new Date(year, month - 1, day);
    const isReal = date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;

    return isReal ? date : null;
}

/**
 * Las reglas. Cada una recibe (valor, parámetro, contexto) y devuelve el mensaje de
 * error, o null si el valor es válido. `contexto.type` es el type del <input> y
 * `contexto.read(nombre)` lee otro campo del formulario.
 */
export const validators = {
    required: (value) => (value === '' ? 'Rellena este campo.' : null),

    min(value, param, { type }) {
        const n = Number(param);
        if (type === 'number') {
            return toNumber(value, type) >= n ? null : `Mínimo ${n}.`;
        }
        return value.length >= n ? null : `Mínimo ${n} caracteres.`;
    },

    max(value, param, { type }) {
        const n = Number(param);
        if (type === 'number') {
            return toNumber(value, type) <= n ? null : `Máximo ${n}.`;
        }
        return value.length <= n ? null : `Máximo ${n} caracteres.`;
    },

    email: (value) => (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) ? null : 'Escribe un correo válido.'),

    plate: (value) =>
        MODERN_PLATE.test(value) || OLD_PLATE.test(value) ? null : 'Matrícula no válida. Ejemplo: 1234 BCD.',

    date: (value) => (parseDate(value) ? null : 'Fecha no válida.'),

    kmMin(value, param, { type }) {
        const km = toNumber(value, type);
        const min = Number(param);

        if (km === null) {
            return 'Escribe los kilómetros en números.';
        }
        return km >= min ? null : `No puede ser menor que ${min.toLocaleString('es-ES')} km.`;
    },

    confirmed: (value, param, { read }) => (value === read(param) ? null : 'No coinciden.'),
};

/**
 * Pasa un valor por una lista de reglas y devuelve el primer error (o null).
 * Se queda en el primer fallo para no apilar mensajes: se corrige de uno en uno.
 */
export function validateValue(specs, value, context) {
    for (const spec of specs) {
        const [name, param] = spec.split(':');

        if (name !== 'required' && value === '') {
            continue; // campo opcional vacío: nada que comprobar
        }

        const message = validators[name](value, param, context);
        if (message) {
            return message;
        }
    }

    return null;
}

/**
 * Lo que devuelve Laravel en `$errors->messages()` es { campo: ['msg', …] }; aquí solo
 * hace falta el primer mensaje de cada campo.
 */
function firstMessages(messages) {
    return Object.fromEntries(
        Object.entries(messages ?? {}).map(([field, list]) => [field, Array.isArray(list) ? list[0] : list]),
    );
}

document.addEventListener('alpine:init', () => {
    Alpine.data('validatedForm', (rules, serverErrors = {}) => ({
        errors: {}, // lo que se muestra
        touched: {}, // campos que el usuario ya ha abandonado
        values: {}, // copia de los valores: es lo que hace reactivo a `valid`
        server: {}, // errores del servidor aún vigentes (se descartan al editar el campo)
        submitted: false,

        init() {
            this.server = firstMessages(serverErrors);
            this.snapshot();

            // Un campo que el servidor ya rechazó se enseña desde el principio.
            Object.keys(this.server).forEach((field) => {
                this.touched[field] = true;
                this.refresh(field);
            });

            // Un solo oyente para todo el formulario (en vez de uno por campo): los
            // eventos focusout e input "burbujean" desde cada campo hasta el <form>.
            this.$root.addEventListener('focusout', (event) => this.onBlur(event.target.name));
            this.$root.addEventListener('input', (event) => this.onInput(event.target.name));
            this.$root.addEventListener('change', (event) => this.onInput(event.target.name));
        },

        /** El <form> aunque el x-data esté en un contenedor. */
        get form() {
            return this.$root.matches('form') ? this.$root : this.$root.querySelector('form');
        },

        /** Valor actual de un campo, leído del DOM (así vale con Flux, Livewire o HTML a pelo). */
        read(name) {
            const field = this.form?.elements.namedItem(name);

            if (!field) {
                return '';
            }
            // Casilla: marcada o no. Grupo de radios (RadioNodeList): el valor del marcado.
            if (field.type === 'checkbox') {
                return field.checked ? field.value || 'on' : '';
            }

            return typeof field.value === 'string' ? field.value.trim() : '';
        },

        typeOf(name) {
            return this.form?.elements.namedItem(name)?.type ?? 'text';
        },

        /** Vuelve a copiar los valores del DOM (el autocompletado del navegador no avisa). */
        snapshot() {
            Object.keys(rules).forEach((field) => {
                this.values[field] = this.read(field);
            });
        },

        /** Mensaje de error del campo según las reglas, o null. Solo lee `values`. */
        check(name) {
            const value = this.values[name] ?? '';

            return validateValue(rules[name] ?? [], value, {
                type: this.typeOf(name),
                read: (other) => this.values[other] ?? this.read(other),
            });
        },

        /**
         * Decide qué se muestra en un campo: el error de cliente (si ya toca avisar) o,
         * si no hay, el del servidor que aún no se ha corregido.
         */
        refresh(name) {
            const client = this.touched[name] || this.submitted ? this.check(name) : null;
            const message = client ?? this.server[name] ?? null;

            if (message) {
                this.errors[name] = message;
            } else {
                delete this.errors[name];
            }
        },

        onBlur(name) {
            if (!name) {
                return;
            }
            this.touched[name] = true;
            this.values[name] = this.read(name);
            this.refresh(name);
        },

        onInput(name) {
            if (!name) {
                return;
            }
            this.values[name] = this.read(name);
            // Editar el campo invalida el error que dio el servidor sobre el valor anterior.
            delete this.server[name];
            this.refresh(name);

            // Al cambiar una contraseña hay que volver a mirar su confirmación.
            Object.entries(rules).forEach(([other, specs]) => {
                if (specs.includes(`confirmed:${name}`) && this.touched[other]) {
                    this.refresh(other);
                }
            });
        },

        /**
         * Se llama desde x-on:submit. Si algo no cumple las reglas, se corta el envío y
         * se lleva el foco al primer fallo. Si todo cumple, no se toca el evento: el
         * formulario se envía y el servidor valida otra vez.
         */
        submit(event) {
            this.submitted = true;
            this.snapshot();

            const invalid = Object.keys(rules).filter((name) => this.check(name) !== null);
            Object.keys(rules).forEach((name) => {
                this.touched[name] = true;
                this.refresh(name);
            });

            if (invalid.length === 0) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation(); // que wire:submit (si lo hay) no envíe nada
            this.$nextTick(() => this.form.elements.namedItem(invalid[0])?.focus());
        },

        get valid() {
            return Object.keys(rules).every((name) => this.check(name) === null);
        },

        error(name) {
            return this.errors[name] ?? '';
        },

        hasError(name) {
            return Boolean(this.errors[name]);
        },

        /**
         * Atributos de accesibilidad de un campo, listos para `x-bind="field('email')"`:
         * aria-invalid (lo anuncian los lectores de pantalla), data-invalid (lo usa Flux
         * para pintar el borde) y aria-describedby (une el campo con su mensaje, que debe
         * tener id="<campo>-error"). Son funciones para que Alpine las reevalúe solas.
         */
        field(name) {
            const form = this;

            return {
                ':aria-invalid'() {
                    return form.hasError(name) ? 'true' : null;
                },
                ':data-invalid'() {
                    return form.hasError(name) ? '' : null;
                },
                ':aria-describedby'() {
                    return form.hasError(name) ? `${name}-error` : null;
                },
            };
        },
    }));
});
