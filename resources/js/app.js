/**
 * Entrada de JS de Garagic.
 *
 * Alpine ya viene dentro de Livewire (no se instala aparte): cada módulo se registra
 * con `document.addEventListener('alpine:init', () => Alpine.data(...))`, y Livewire
 * dispara ese evento al arrancar.
 *
 * Carga automática: todo archivo `resources/js/garagic/*.js` se importa solo, así que
 * añadir una interacción nueva es crear un módulo ahí; no hay que editar este archivo.
 * `eager: true` los importa ya (no bajo demanda): hacen falta antes de que Alpine arranque.
 *
 * Módulos actuales:
 *   rough.js  subrayado "a mano" de la navegación
 *   ui.js     pestañas, filtros, colapsables, tema y contador animado
 *   forms.js  validatedForm: validación en el navegador (el servidor tiene la última palabra)
 *   cards.js  giro, inclinación y baraja de las cartas
 */
import.meta.glob('./garagic/*.js', { eager: true });
