/**
 * Reglas propias del formulario de ritual nuevo para `validatedForm` (forms.js).
 *
 * POR QUÉ AQUÍ Y NO EN forms.js
 * forms.js trae las reglas que comparten varios formularios (required, date, kmMin...).
 * Estas dos solo tienen sentido al registrar un ritual, así que se añaden desde este
 * módulo al mismo objeto `validators`. Basta con importarlo: forms.js se carga primero.
 *
 * Como siempre, es solo comodidad: el servidor vuelve a validar y tiene la última palabra.
 */
import { validators, toNumber, parseDate } from './forms.js';

/**
 * cost: el coste no puede ser negativo. Cero SÍ vale: un ritual hecho en casa no cuesta
 * nada y es un dato real (por eso el campo es obligatorio pero acepta 0).
 */
validators.cost = (value, _param, { type }) => {
    const amount = toNumber(value, type);

    if (amount === null) {
        return 'Escribe el coste en números.';
    }

    return amount >= 0 ? null : 'El coste no puede ser negativo.';
};

/**
 * kmNotNegative: los kilómetros no pueden ser negativos. Es la versión "sin cifras en el
 * mensaje" de kmMin:0 (los dígitos de IM Fell, de estilo antiguo, no se leen bien: un 0 parece
 * una "o"). Que el km sea coherente con el historial del vehículo lo comprobará el servidor,
 * que es quien conoce los demás rituales.
 */
validators.kmNotNegative = (value, _param, { type }) => {
    const km = toNumber(value, type);

    if (km === null) {
        return 'Escribe los kilómetros en números.';
    }

    return km >= 0 ? null : 'Los kilómetros no pueden ser negativos.';
};

/**
 * notFuture: un ritual es algo que ya has hecho, así que su fecha no puede ser de mañana
 * en adelante. Lo que está por venir es un aviso, no un ritual.
 */
validators.notFuture = (value) => {
    const day = parseDate(value);

    if (!day) {
        return null; // si no es una fecha real, ya lo dice la regla `date`
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0); // comparar días, no horas

    return day <= today ? null : 'La fecha no puede ser futura.';
};
