# Encargo de diseño: identidad visual de Garagic (prototipo)

> Reformulación de `docs/DESIGN.md` (conversación de diseño) como encargo ejecutable.
> Flujo: planificador (Opus) → ejecutores (Sonnet). Texto escrito para agentes que arrancan en frío.

## 0. Encargo en una frase

Diseñar y prototipar **toda la identidad visual** de Garagic y las pantallas necesarias para partir de ellas. **Solo capa visual**: datos falsos, sin BD nueva, sin IA real, sin lógica de negocio. Es un prototipo para validar el aspecto; el copyright de los recursos se resuelve después.

## 1. Producto

Garagic es una app web de mantenimiento de vehículos (TFC de ciclo DAM, defendido ante tribunal). Cada vehículo es una **carta de tarot**; mantenerla al día sube su **vitalidad** (0–100) y desbloquea **logros**; un **Oráculo** de IA gratuita responde dudas mecánicas.

- **Usuario real**: persona con 1–4 vehículos que no quiere que se le pase la ITV, el seguro, el aceite o las ruedas, y quiere saber cuánto le cuesta el coche.
- **Dos roles**: usuario y admin (el admin gestiona el catálogo de tipos de tarea y de logros).
- **Idioma**: interfaz en español. Código, tablas y nombres de componentes/archivos en inglés (decisión D0).

### Utilidad (lo que se defiende)
1. Avisos por fecha o por km (ITV, seguro, aceite, ruedas).
2. Historial y gasto total por vehículo (también para enseñárselo a un comprador).
3. Oráculo: describes un síntoma o pegas una factura → causas probables y urgencia, o entradas listas para aceptar.

### Diversión (capa por encima)
- **Vitalidad 0–100**: barra de vida del coche. Si no hay ningún mantenimiento registrado → «sin datos» + indicación de qué hacer (decisión D2).
- **Logros**: «primer ritual», «ITV al día», «10 rituales», «3 meses sin nada vencido». Se muestran como **sellos**.
- **La carta**: cada coche es una carta que gira, con su número romano. No aporta función; hace que apetezca abrir la app.

### Diferenciación (honestidad)
Apps de mantenimiento ya hay. Garagic se distingue por el **Oráculo** y por la **identidad visual**. Por eso la estética es el activo principal: tiene que estar muy bien hecha y ser coherente en todas las pantallas.

## 2. Dirección de identidad

### Fijado por el cliente (no se discute)
- Caligráfico y místico. Paleta: **hueso, tinta y grises**. Un único color de acento: **bermellón, reservado en exclusiva al sello «IA»** (todo contenido de IA lleva ese sello; ningún otro elemento usa bermellón ni rojo).
- Radio **0**, bordes de **1 px**, **cero sombras**. Colores como **variables CSS**.
- Tipografía caligráfica **solo en títulos y números grandes**. Máx. **3 familias**. Propuesta del cliente: títulos/números grandes → **IM Fell English** (tinta impresa gastada) o **Fraunces** (más quirky); datos → **IBM Plex Sans** o **Public Sans**; km, precios y fechas → **IBM Plex Mono** (aspecto de ticket). **Prohibido**: Inter, Cormorant, fuentes script tipo Pinyon (salvo un número o una firma).
- Iconos: **una sola familia y un solo grosor** (Lucide vía `php artisan flux:icon`, o Phosphor thin/light). Mezclar familias está prohibido.
- Animaciones de **200–300 ms** y respeto a `prefers-reduced-motion` (excepción: la entrada de carta al abrir la app puede durar algo más).
- Estética de «libreta de taller»: grabados reales, grano de papel sutil, trazos a mano.

### Libre (decide el planificador, con razones)
Hexadecimales exactos, escala tipográfica, composición de cada pantalla, estructura de navegación, el hero de la portada, cómo se codifica la urgencia sin usar rojo/verde (pista: tratamiento de tinta, rayado, texto literal, desgaste de la carta), nombres de arcanos.

### Anti-slop (obligatorio)
El slop viene de dejar los valores por defecto de una librería. Lo que grita IA: `rounded-xl`, sombras suaves, Inter, tarjetas idénticas, iconos en círculo de color, degradados, glassmorphism, emojis como iconos, fondo crema + acento terracota. **Sobrescribir los tokens** (radio 0, borde 1 px, sombras none, colores y fuentes propios) importa más que elegir librería. Prohibido: partículas, brillos animados, fondos con movimiento, sonidos, más de 4 familias de animación distintas en total.

Aplica el proceso de la skill `frontend-design`: (1) plan de tokens compacto (color 4–6 hex con nombre, tipografía y roles, layout con wireframes ASCII, principios); (2) **revisarlo contra los defaults genéricos y corregirlo** diciendo qué se cambió y por qué; (3) construir; (4) autocrítica («quita un accesorio»). Gasta la audacia en **un solo sitio: la carta**; el resto, callado y disciplinado.

**Prueba de coherencia**: si dos pantallas parecen de apps distintas, algo se ha colado de otra librería.

## 3. Tecnología (reparto)

- **Blade**: pinta la página. **Tailwind v4** (CSS-first: `@theme` en `resources/css/app.css`, sin `tailwind.config.js`). **Alpine.js**: interacción solo en navegador (giro, inclinación, baraja, pestañas, modales); ya viene con Livewire, no instalarlo aparte. **Livewire 4**: solo donde haya estado de servidor (formularios, el Oráculo simulado con `wire:loading`); componentes de archivo único `⚡*.blade.php`. **Flux UI gratuito**: sin `accordion`, `table`, modal avanzado, etc. (dan «Unable to locate a class or view for component») → Blade/Alpine/Tailwind a mano. **3D = CSS 3D** (`perspective`, `transform-style: preserve-3d`, `backface-visibility`), **sin three.js ni WebGL**.
- Regla: lógica y datos → Livewire/PHP; solo visual → Alpine/Tailwind.
- Flux se themea sobrescribiendo variables CSS: el radio 0 y las sombras none deben llegar también a los componentes de Flux (verificarlo en el navegador/CSS compilado, no suponerlo).
- Proyecto con modo oscuro (`.dark`, `@fluxAppearance`, página de ajustes de apariencia). El prototipo debe funcionar en claro y oscuro intercambiando **solo variables** (oscuro = papel de tinta con texto hueso; el bermellón sigue siendo solo IA).
- Vite se invoca con `npm run dev` / `npm run build` (vite-plus). Existe una directiva `@fonts` en `resources/views/partials/head.blade.php`: entender cómo funciona antes de añadir fuentes. Preferido: Fontsource por npm (sin pedir nada a Google).

## 4. Recursos reales (primero, antes de dibujar nada)

Elegir y descargar **antes** de componer pantallas, a `public/img/` (optimizados, < 500 KB cada uno; usar webp/svg si hay herramienta):

1. **Grabados** de vehículos y mecánica (≥ 4 distintos: coche, moto, furgoneta/camión o motor, un detalle de rueda/llave): Wikimedia Commons, Internet Archive (manuales y revistas de motor antiguas), Met Open Access, dibujos de patentes antiguas. Verificar la URL real con una petición antes de usarla. Un grabado real manda sobre cualquier ilustración generada.
2. **Marco de carta**: estructura de las cartas Rider-Waite-Smith (1909, dominio público): borde doble, número romano arriba, nombre abajo. Se copia la estructura, no el dibujo; el marco se dibuja en SVG/CSS.
3. **Dorso de carta**: un patrón SVG común a todas las cartas, hecho a mano (guilloché/geométrico).
4. **Iconos**: una familia, un grosor, 6–8 iconos (aceite, rueda, llave, escudo/seguro, ITV, gasto, aviso, Oráculo…). game-icons.net y SVG Repo permitidos (atribución).
5. **Papel/grano**: filtro SVG `feTurbulence` (cero descargas).
6. **Trazos a mano**: `rough-notation` (subrayado a mano en el elemento activo de la navegación) / `rough.js` si hace falta.
7. **Fuentes** por Fontsource (npm).

Se dan permisos para: descargar imágenes de internet, usar imágenes del usuario, e instalar dependencias **npm de front-end** (fuentes, rough-notation) y importar iconos Lucide con `flux:icon`. **No** instalar paquetes de Composer. Registrar la procedencia de cada recurso en `public/img/FUENTES.md` (fuente, URL, licencia conocida) para poder limpiar el copyright después.

## 5. Piezas propias (componentes Blade en `resources/views/components/`, nombres en inglés)

La carta, la vitalidad, el sello de IA y las filas de ritual no existen en ninguna librería: son la mayor parte del aspecto. Como mínimo:

| Componente | Responsabilidad |
|---|---|
| `tarot-card` | Frente (grabado + número romano + nombre + matrícula), **dorso** (patrón común + datos secundarios: km, matrícula, ITV), variable de desgaste. Props: vehículo (datos falsos), `vitality`, `flipped`, tamaño. Giro y teclado accesibles. |
| `tarot-deck` | Baraja del garaje: cartas apiladas ligeramente torcidas (±1–2°); al clicar una sale al frente. |
| `vitality-bar` | Barra de vitalidad 0–100 en CSS puro, estado «sin datos», texto literal del estado (nunca solo color). |
| `ai-seal` | Sello «IA» bermellón; único uso de ese color. |
| `ritual-row` | Fila de ritual/aviso: tipo, fecha o km de vencimiento, estado, coste (mono). Rápida de leer y de usar. |
| `achievement-stamp` | Sello de logro: desbloqueado y bloqueado; se estampa (scale + leve rotación, 250 ms). |
| `arcana-card` | Respuesta del Oráculo: arcano según urgencia baja/media/alta (pista: Rueda de la Fortuna para media), con la **urgencia en texto literal al lado** y `ai-seal`. |
| `ticket` / `stat` | Bloques de cifras con aspecto de recibo (Plex Mono): gasto total, km, fechas. |

Más los necesarios: marco/panel de página, cabecera/navegación (con subrayado Rough Notation en el activo), estado vacío, campo de formulario y botón con los tokens (sobre Flux), pie, y la **mecánica de la carta como mixin reutilizable**.

**Desgaste con la vitalidad**: una sola variable CSS (`--vitality`) controla grano, sepia y desaturación. Con 95 se ve limpia; con 30, castigada. Es lo más «videojuego» y se explica en un minuto: fórmula corta, comentada con el porqué.

## 6. Movimiento (barato y con significado)

Familias de animación (máx. 4, reutilizadas): **(a)** movimiento de carta: giro 3D con reverso, inclinación al pasar el ratón (la carta sigue el cursor unos grados, brillo de papel muy tenue; ~20 líneas de Alpine; desactivada en táctil y con reduced motion), sacar-al-frente en la baraja, entrada de carta al abrir la app, «sacar carta» del Oráculo; **(b)** sello que se estampa; **(c)** relleno de la barra de vitalidad; **(d)** transición simple de pestañas/colapsables. Duración 200–300 ms; la entrada de carta puede llegar a ~700 ms. Todo con `motion-reduce` equivalente estático. La pantalla de rituales **no** es peliculera: tiene que ser rápida.

## 7. Pantallas (mínimo viable para partir de ellas)

Publicas y de app. Cada una con wireframe ASCII en el plan, versión móvil incluida.

1. **Portada** (`/`): el hero es la carta (entrada, giro, inclinación). Qué es Garagic en una frase honesta; acceso a registro/login.
2. **Auth**: login, registro (con elección de rol visible solo si procede), olvidé contraseña, restablecer, verificar email. Mismo marco, mismas piezas.
3. **La baraja** (panel del usuario): `tarot-deck` + resumen de avisos próximos/vencidos de todos los vehículos.
4. **Vehículo / Carta**: carta grande con giro, vitalidad, avisos (fecha o km), gasto total, últimos rituales, sellos ganados.
5. **Alta y edición de vehículo**: formulario (matrícula, marca/modelo, km, fecha de ITV/seguro con `<input type="date">` estilizado).
6. **Rituales** (mantenimientos): lista rápida + registro de un ritual nuevo (tipo de tarea, fecha, km, coste). Sin florituras.
7. **Historial y gasto** por vehículo: tabla hecha a mano (no existe `flux:table`), totales tipo ticket.
8. **Logros**: catálogo como muro de sellos, desbloqueados y bloqueados.
9. **Oráculo**: entrada (síntoma o pegar factura), estados vacío / cargando (`wire:loading`) / respondido; la respuesta saca carta con urgencia literal + sello «IA»; entradas propuestas con **Aceptar / Descartar** (la IA nunca guarda nada sin aceptación); recordatorio visible de que la app funciona sin IA.
10. **Admin**: catálogo de tipos de tarea y de logros (listas + formulario).
11. **Ajustes** (perfil, seguridad, apariencia, borrar cuenta): ya existen del starter kit; deben adoptar la identidad vía tokens y componentes sin romper su comportamiento.
12. **Guía de estilo** (`/prototipo/estilo`): colores, escala tipográfica, iconos, y cada componente en todos sus estados (incluida la carta con vitalidad 95/72/45/30/«sin datos», claro y oscuro). Es la prueba de coherencia y la pieza que se enseña al tribunal.
13. *(Si cabe)* Errores 403/404/500 en el mismo lenguaje.

Las vistas del starter kit existentes (welcome, dashboard, layouts, ajustes) se **restilizan**; no se rehace su comportamiento.

## 8. Datos falsos (centralizados y fáciles de borrar)

Fecha de hoy en el prototipo: **12/10/2026**. 4 vehículos con perfiles distintos: vitalidad ~95, ~72, ~30 y uno **sin datos**; mezcla de coche, moto y furgoneta; matrículas españolas; km realistas; ITV/seguro con fechas relativas a hoy (una vencida, una próxima, una lejana); 8–12 rituales con costes en €; los 4 logros de arriba; textos del Oráculo creíbles (síntoma → 2–3 causas probables + urgencia). Un usuario falso y un admin falso. Todo en un único lugar identificable para sustituirlo por Eloquent más adelante (sin crear modelos ni migraciones).

Microcopy: español claro, frase en minúscula normal, voz activa, botones que dicen lo que hacen («Guardar ritual», no «Enviar»), estados vacíos que invitan a actuar, errores que explican qué falló y cómo arreglarlo. El vocabulario temático (carta, ritual, vitalidad, sello, Oráculo) solo para lo que ya nombra el proyecto; lo demás, llano y legible para alguien nuevo.

## 9. Dónde se puede tocar y dónde no

**Sí**: `resources/css/app.css`, `resources/js/app.js`, `resources/views/**` (layouts, partials, components, pages, `flux/` iconos), `public/**` (assets), `package.json`/lock (solo deps front-end), `routes/web.php` (solo rutas de prototipo), `config/` solo si una fuente lo exige y se anota.
**No**: `database/` (migraciones, modelos, esquema E-R), `app/` (salvo que sea imprescindible para una vista y se anote), `composer.json`/`composer.lock`, `.env`, `vendor/`, `storage/`, `tests/` (no borrar ni editar tests existentes), nada de `app/Ai/`.
**Rutas de prototipo**: `Route::view` bajo prefijo `/prototipo/*`, nombres `prototype.*`, sin `auth`, **solo si `app()->isLocal()`**, para poder verlas sin BD ni login. El shell de app del prototipo debe renderizar sin usuario autenticado (usuario falso).
**No romper el starter kit**: conservar nombres de rutas, `name` de campos, `wire:` bindings, acciones de formularios de Fortify y atributos `data-test`. Antes de cambiar cualquier texto visible, buscar en `tests/` si hay aserciones sobre él (p. ej. `tests/Feature/Settings/SecurityTest.php` comprueba «Update password») y conservarlo o mantener los tests en verde.
**Cambios sin commitear de David** que no se pueden perder: `resources/views/pages/auth/login.blade.php` (acordeón de prueba en Alpine: ejemplo de aprendizaje), `register.blade.php`, `routes/settings.php`, `resources/css/app.css`. Hay copia de seguridad en el scratchpad (`backup-david/`). Leerlos antes de editar, partiendo de su contenido actual.

## 10. Calidad y verificación

- Responsive hasta móvil (gutter 16 px, sin scroll horizontal), foco de teclado visible, contraste AA, urgencia nunca solo por color, giro de carta operable con teclado, `prefers-reduced-motion` respetado.
- Verificación mínima: `npm run build` sin errores; cada ruta de prototipo responde 200 sin excepciones de Blade (revisar también `storage/logs/laravel.log`); `vendor/bin/pint --dirty --format agent` si hay PHP; ejecutar los tests existentes de Auth y Settings (`php artisan test --compact tests/Feature/Auth tests/Feature/Settings`); comprobar a ojo (captura si hay herramienta) claro/oscuro, móvil y escritorio.
- Nada de commits (David revisa el diff antes). Nada de preguntas al usuario: ante la duda, decisión razonable documentada en el informe final.

## 11. Entregables del planificador

Un plan ejecutable por agentes que arrancan en frío: tokens exactos (hex, fuentes, escala, espaciados, variables `--vitality`/desgaste), API de cada componente (props/slots), wireframe ASCII por pantalla, lista cerrada de recursos con URL verificada, mapa de rutas, orden de trabajo en **paquetes** (WP0 cimientos y recursos → WP1 tokens + layout + componentes → WP2… pantallas en paralelo con **archivos de propiedad disjuntos** → WP final de QA), criterios de aceptación por paquete y riesgos (Flux sin radio 0, dependencias de assets, colisiones de build en paralelo).
