<?php

/**
 * Datos FALSOS del prototipo visual (solo rutas /prototipo/*, solo en local).
 *
 * Por qué un array y no modelos: el prototipo valida el aspecto sin tocar la BD.
 * Cuando existan Vehicle, Maintenance, Achievement..., las vistas recibirán colecciones
 * Eloquent con estas mismas claves y este archivo se borrará.
 * En el prototipo, hoy es 12/10/2026: todas las fechas son relativas a ese día.
 * Los valores calculados (vitalidad, estados, totales) están escritos a mano a propósito.
 */

return [
    'today' => '2026-10-12',

    'user' => ['id' => 1, 'name' => 'Lucía Ferrer', 'email' => 'lucia@garagic.test', 'initials' => 'LF', 'role' => 'user'],
    'admin' => ['id' => 2, 'name' => 'Marta Ibáñez', 'email' => 'admin@garagic.test', 'initials' => 'MI', 'role' => 'admin'],

    // Vehículo que sale al frente de la baraja: el que más atención necesita.
    'deckFocus' => 3,

    'taskTypes' => [
        'itv' => ['key' => 'itv', 'name' => 'ITV', 'icon' => 'clipboard-check', 'interval_months' => 12, 'interval_km' => null, 'uses' => 2],
        'insurance' => ['key' => 'insurance', 'name' => 'Seguro', 'icon' => 'shield-check', 'interval_months' => 12, 'interval_km' => null, 'uses' => 3],
        'oil' => ['key' => 'oil', 'name' => 'Cambio de aceite', 'icon' => 'droplet', 'interval_months' => 12, 'interval_km' => 15000, 'uses' => 3],
        'tyres' => ['key' => 'tyres', 'name' => 'Neumáticos', 'icon' => 'disc-3', 'interval_months' => null, 'interval_km' => 40000, 'uses' => 1],
        'brakes' => ['key' => 'brakes', 'name' => 'Pastillas de freno', 'icon' => 'wrench', 'interval_months' => null, 'interval_km' => 30000, 'uses' => 1],
        'chain' => ['key' => 'chain', 'name' => 'Engrase de cadena', 'icon' => 'wrench', 'interval_months' => null, 'interval_km' => 1000, 'uses' => 1],
    ],

    // Arte de la carta: el vehículo 1 usa la foto de David (clave 'photo', fondo blanco que la
    // carta elimina con multiply); los demás, grabados de dominio público (clave 'engraving').
    'vehicles' => [
        1 => [
            'id' => 1, 'numeral' => 'I', 'type' => 'car', 'type_label' => 'Coche',
            'brand' => 'Seat', 'model' => 'Ibiza', 'name' => 'Seat Ibiza', 'plate' => '1234 MNP', 'year' => 2026, 'km' => 9420,
            'photo' => '/images/seat-ibiza-2026.webp', 'vitality' => 95,
            // Coche nuevo de enero de 2026: la primera ITV no toca hasta los 4 años.
            'last_itv' => null, 'itv_due' => '2030-01-20', 'last_insurance' => '2026-01-20', 'insurance_due' => '2027-01-20',
            'spent_total' => 737.60, 'spent_year' => 737.60, 'cost_per_km' => '0,08 €/km', 'rituals_count' => 3,
            'card' => [
                'numeral' => 'I', 'title' => 'Seat Ibiza', 'plate' => '1234 MNP', 'photo' => '/images/seat-ibiza-2026.webp',
                'back' => ['Kilómetros' => '9.420 km', 'Matrícula' => '1234 MNP', 'Próxima ITV' => '20/01/2030', 'Seguro hasta' => '20/01/2027'],
            ],
        ],
        2 => [
            'id' => 2, 'numeral' => 'II', 'type' => 'motorbike', 'type_label' => 'Moto',
            'brand' => 'Honda', 'model' => 'CB500F', 'name' => 'Honda CB500F', 'plate' => '7390 JHN', 'year' => 2017, 'km' => 31250,
            'engraving' => 'moto-werner-1898', 'vitality' => 72,
            'last_itv' => '2025-02-08', 'itv_due' => '2027-02-08', 'last_insurance' => '2025-10-21', 'insurance_due' => '2026-10-21',
            'spent_total' => 305.30, 'spent_year' => 12.00, 'cost_per_km' => '0,02 €/km', 'rituals_count' => 4,
            'card' => [
                'numeral' => 'II', 'title' => 'Honda CB500F', 'plate' => '7390 JHN', 'engraving' => 'moto-werner-1898',
                'back' => ['Kilómetros' => '31.250 km', 'Matrícula' => '7390 JHN', 'Próxima ITV' => '08/02/2027', 'Seguro hasta' => '21/10/2026'],
            ],
        ],
        3 => [
            'id' => 3, 'numeral' => 'III', 'type' => 'van', 'type_label' => 'Furgoneta',
            'brand' => 'Citroën', 'model' => 'Berlingo', 'name' => 'Citroën Berlingo', 'plate' => '2156 GFB', 'year' => 2009, 'km' => 214800,
            'engraving' => 'van-montagu-1928', 'vitality' => 30,
            'last_itv' => '2025-09-28', 'itv_due' => '2026-09-28', 'last_insurance' => '2026-01-15', 'insurance_due' => '2027-01-15',
            'spent_total' => 819.40, 'spent_year' => 683.20, 'cost_per_km' => '0,04 €/km', 'rituals_count' => 4,
            'card' => [
                'numeral' => 'III', 'title' => 'Citroën Berlingo', 'plate' => '2156 GFB', 'engraving' => 'van-montagu-1928',
                'back' => ['Kilómetros' => '214.800 km', 'Matrícula' => '2156 GFB', 'Próxima ITV' => '28/09/2026 (vencida)', 'Seguro hasta' => '15/01/2027'],
            ],
        ],
        4 => [
            'id' => 4, 'numeral' => 'IV', 'type' => 'car', 'type_label' => 'Coche',
            'brand' => 'Renault', 'model' => 'Clio', 'name' => 'Renault Clio', 'plate' => '0943 HBZ', 'year' => 2012, 'km' => 142000,
            'engraving' => 'car-almond-1899', 'vitality' => null, // sin datos (D2)
            'last_itv' => null, 'itv_due' => null, 'last_insurance' => null, 'insurance_due' => null,
            'spent_total' => 0.0, 'spent_year' => 0.0, 'cost_per_km' => null, 'rituals_count' => 0,
            'card' => [
                'numeral' => 'IV', 'title' => 'Renault Clio', 'plate' => '0943 HBZ', 'engraving' => 'car-almond-1899',
                'back' => ['Kilómetros' => '142.000 km', 'Matrícula' => '0943 HBZ', 'Próxima ITV' => 'Sin datos', 'Seguro hasta' => 'Sin datos'],
            ],
        ],
    ],

    // Avisos (en la app real se calculan: último mantenimiento + intervalo, decisión D1).
    // status: overdue | soon | ok | none. label: el texto literal que se muestra.
    'warnings' => [
        ['id' => 1, 'vehicle_id' => 3, 'task' => 'itv', 'status' => 'overdue', 'due_date' => '2026-09-28', 'due_km' => null, 'label' => 'Vencida hace 14 días'],
        ['id' => 2, 'vehicle_id' => 3, 'task' => 'oil', 'status' => 'overdue', 'due_date' => null, 'due_km' => 210000, 'label' => 'Pasado por 4.800 km'],
        ['id' => 3, 'vehicle_id' => 2, 'task' => 'insurance', 'status' => 'soon', 'due_date' => '2026-10-21', 'due_km' => null, 'label' => 'Vence en 9 días'],
        ['id' => 4, 'vehicle_id' => 2, 'task' => 'oil', 'status' => 'soon', 'due_date' => null, 'due_km' => 32000, 'label' => 'Faltan 750 km'],
        ['id' => 5, 'vehicle_id' => 2, 'task' => 'itv', 'status' => 'ok', 'due_date' => '2027-02-08', 'due_km' => null, 'label' => 'Al día'],
        ['id' => 6, 'vehicle_id' => 2, 'task' => 'chain', 'status' => 'ok', 'due_date' => null, 'due_km' => 31800, 'label' => 'Faltan 550 km'],
        ['id' => 7, 'vehicle_id' => 3, 'task' => 'insurance', 'status' => 'ok', 'due_date' => '2027-01-15', 'due_km' => null, 'label' => 'Al día'],
        ['id' => 8, 'vehicle_id' => 3, 'task' => 'brakes', 'status' => 'ok', 'due_date' => null, 'due_km' => 241200, 'label' => 'Faltan 26.400 km'],
        ['id' => 9, 'vehicle_id' => 1, 'task' => 'itv', 'status' => 'ok', 'due_date' => '2030-01-20', 'due_km' => null, 'label' => 'Al día'],
        ['id' => 10, 'vehicle_id' => 1, 'task' => 'insurance', 'status' => 'ok', 'due_date' => '2027-01-20', 'due_km' => null, 'label' => 'Al día'],
        ['id' => 11, 'vehicle_id' => 1, 'task' => 'oil', 'status' => 'ok', 'due_date' => null, 'due_km' => 23100, 'label' => 'Faltan 13.680 km'],
        ['id' => 12, 'vehicle_id' => 1, 'task' => 'tyres', 'status' => 'ok', 'due_date' => null, 'due_km' => 43900, 'label' => 'Faltan 34.480 km'],
        ['id' => 13, 'vehicle_id' => 4, 'task' => null, 'status' => 'none', 'due_date' => null, 'due_km' => null, 'label' => 'Sin rituales registrados'],
    ],

    // Rituales (mantenimientos) hechos: 11, con coste en euros. El Ibiza es de 2026 y no tiene ITV todavía.
    'rituals' => [
        ['id' => 1, 'vehicle_id' => 1, 'task' => 'insurance', 'date' => '2026-01-20', 'km' => 15, 'cost' => 412.60, 'workshop' => 'Aseguradora Prisma'],
        ['id' => 2, 'vehicle_id' => 1, 'task' => 'tyres', 'date' => '2026-04-11', 'km' => 3900, 'cost' => 236.00, 'workshop' => 'Neumáticos Ruiz'],
        ['id' => 3, 'vehicle_id' => 1, 'task' => 'oil', 'date' => '2026-07-10', 'km' => 8100, 'cost' => 89.00, 'workshop' => 'Talleres Hnos. García'],
        ['id' => 4, 'vehicle_id' => 2, 'task' => 'insurance', 'date' => '2025-10-21', 'km' => 16500, 'cost' => 189.00, 'workshop' => 'Aseguradora Prisma'],
        ['id' => 5, 'vehicle_id' => 2, 'task' => 'oil', 'date' => '2025-11-20', 'km' => 17000, 'cost' => 64.50, 'workshop' => 'MotoTaller Arrieta'],
        ['id' => 6, 'vehicle_id' => 2, 'task' => 'chain', 'date' => '2026-09-15', 'km' => 30800, 'cost' => 12.00, 'workshop' => 'Hecho en casa'],
        ['id' => 7, 'vehicle_id' => 2, 'task' => 'itv', 'date' => '2025-02-08', 'km' => 12900, 'cost' => 39.80, 'workshop' => 'Estación ITV Sur'],
        ['id' => 8, 'vehicle_id' => 3, 'task' => 'itv', 'date' => '2025-09-28', 'km' => 199500, 'cost' => 41.20, 'workshop' => 'Estación ITV Sur'],
        ['id' => 9, 'vehicle_id' => 3, 'task' => 'oil', 'date' => '2025-02-10', 'km' => 195000, 'cost' => 95.00, 'workshop' => 'Talleres Hnos. García'],
        ['id' => 10, 'vehicle_id' => 3, 'task' => 'insurance', 'date' => '2026-01-15', 'km' => 203000, 'cost' => 534.80, 'workshop' => 'Aseguradora Prisma'],
        ['id' => 11, 'vehicle_id' => 3, 'task' => 'brakes', 'date' => '2026-05-30', 'km' => 211200, 'cost' => 148.40, 'workshop' => 'Talleres Hnos. García'],
    ],

    // Logros del usuario (catálogo del admin + estado de Lucía). tilt: giro del sello en el muro.
    // Por fechas, el 10.º ritual es el del 10/07/2026 (cambio de aceite del Ibiza).
    'achievements' => [
        ['key' => 'first-ritual', 'name' => 'Primer ritual', 'description' => 'Registra tu primer mantenimiento.', 'icon' => 'stamp',
            'rule' => 'first_ritual', 'rule_label' => 'Registra su primer ritual', 'value' => null,
            'unlocked' => true, 'date' => '2025-02-08', 'earned_with' => 2, 'fresh' => false, 'tilt' => '-3deg', 'hint' => null],
        ['key' => 'ten-rituals', 'name' => '10 rituales', 'description' => 'Registra diez mantenimientos.', 'icon' => 'stamp',
            'rule' => 'rituals_count', 'rule_label' => 'Llega a N rituales', 'value' => 10,
            'unlocked' => true, 'date' => '2026-07-10', 'earned_with' => 1, 'fresh' => true, 'tilt' => '2deg', 'hint' => null],
        ['key' => 'itv-ok', 'name' => 'ITV al día', 'description' => 'Todos tus vehículos con la ITV en vigor.', 'icon' => 'clipboard-check',
            'rule' => 'itv_ok', 'rule_label' => 'Tiene la ITV al día', 'value' => null,
            'unlocked' => false, 'date' => null, 'earned_with' => null, 'fresh' => false, 'tilt' => '0deg',
            'hint' => 'Te falta la del Citroën Berlingo: venció el 28/09/2026.'],
        ['key' => 'three-months-clean', 'name' => '3 meses sin nada vencido', 'description' => 'Pasa tres meses seguidos sin avisos vencidos.', 'icon' => 'clock',
            'rule' => 'months_clean', 'rule_label' => 'Pasa N meses sin nada vencido', 'value' => 3,
            'unlocked' => false, 'date' => null, 'earned_with' => null, 'fresh' => false, 'tilt' => '0deg',
            'hint' => 'Llevas 0 de 90 días: hay 2 avisos vencidos.'],
    ],

    // Arcanos del Oráculo (escaneos RWS de 1909 a color; el CSS los unifica con la paleta).
    // low/medium/high = urgencia de la respuesta; showcase = la carta de la portada.
    'arcana' => [
        'low' => ['numeral' => 'XVII', 'name' => 'La Estrella', 'icon' => 'star', 'image' => '/img/arcana/17-la-estrella.webp'],
        'medium' => ['numeral' => 'X', 'name' => 'La Rueda de la Fortuna', 'icon' => 'ship-wheel', 'image' => '/img/arcana/10-rueda-de-la-fortuna.webp'],
        'high' => ['numeral' => 'XVI', 'name' => 'La Torre', 'icon' => 'castle', 'image' => '/img/arcana/16-la-torre.webp'],
        'showcase' => ['numeral' => 'VII', 'name' => 'El Carro', 'icon' => null, 'image' => '/img/arcana/07-el-carro.webp'],
    ],

    // Emblemas de grabado (máscaras de tinta) para los sellos de logro, por clave de logro.
    // El respaldo, si faltara uno, es el icono Lucide del propio logro.
    'emblems' => [
        'first-ritual' => '/img/ornament/emblem-anchor.webp',
        'ten-rituals' => '/img/ornament/emblem-compass.webp',
        'itv-ok' => '/img/ornament/emblem-eagle.webp',
        'three-months-clean' => '/img/ornament/emblem-hourglass.webp',
    ],

    // Dorso real de las cartas (xilografía de 1865 convertida en máscara de tinta) y adornos.
    'cardBack' => '/img/ornament/card-back.webp',
    'ornament' => [
        'divider' => '/img/ornament/divider-fleuron.webp',
        'corner' => '/img/ornament/corner.webp',
    ],

    // Oráculo simulado: un síntoma da 2-3 causas y una urgencia; una factura da entradas para aceptar.
    'oracle' => [
        'samples' => [
            'brakes' => [
                'mode' => 'symptom', 'vehicle_id' => 3, 'urgency' => 'high',
                'question' => 'Al frenar se oye un chirrido metálico y el pedal vibra un poco, sobre todo bajando cuestas.',
                'summary' => 'Es probable que las pastillas estén al límite y que los discos se hayan deformado. Afecta a la frenada: revísalo cuanto antes.',
                'causes' => [
                    ['title' => 'Pastillas gastadas hasta el testigo', 'likelihood' => 'Muy probable', 'detail' => 'El chirrido metálico es el aviso que llevan las pastillas cuando se acaban.'],
                    ['title' => 'Discos de freno alabeados', 'likelihood' => 'Probable', 'detail' => 'La vibración en el pedal al bajar cuestas apunta a discos deformados por el calor.'],
                    ['title' => 'Pinza de freno agarrotada', 'likelihood' => 'Menos probable', 'detail' => 'Si al frenar tira hacia un lado, revisa también las pinzas.'],
                ],
                'advice' => 'Pide cita en un taller esta semana y evita viajes largos hasta entonces. Las pastillas se cambiaron a los 211.200 km: que miren también los discos.',
                'proposals' => [
                    ['kind' => 'reminder', 'task' => 'brakes', 'title' => 'Revisar frenos', 'date' => '2026-10-19', 'km' => null, 'cost' => null, 'detail' => 'Aviso para dentro de 7 días'],
                ],
            ],
            'start' => [
                'mode' => 'symptom', 'vehicle_id' => 2, 'urgency' => 'medium',
                'question' => 'A la moto le cuesta arrancar en frío y alguna vez se cala al ralentí.',
                'summary' => 'Suele ser la batería o las bujías. Puedes seguir usándola, pero no lo dejes más de dos semanas.',
                'causes' => [
                    ['title' => 'Batería débil por el frío', 'likelihood' => 'Probable', 'detail' => 'Con temperaturas bajas una batería cansada no da la corriente de arranque.'],
                    ['title' => 'Bujías gastadas', 'likelihood' => 'Posible', 'detail' => 'Una chispa débil hace que cueste arrancar y que el ralentí sea irregular.'],
                    ['title' => 'Ralentí desajustado o filtro de aire sucio', 'likelihood' => 'Menos probable', 'detail' => 'Si se cala ya en caliente, mira el filtro de aire.'],
                ],
                'advice' => 'Mide la tensión de la batería: en reposo debería dar unos 12,6 V. Si está bien, revisa las bujías en el próximo cambio de aceite, que te toca en 750 km.',
                'proposals' => [
                    ['kind' => 'reminder', 'task' => 'oil', 'title' => 'Revisar batería y bujías', 'date' => '2026-10-26', 'km' => null, 'cost' => null, 'detail' => 'Aviso para dentro de 14 días'],
                ],
            ],
            'invoice' => [
                'mode' => 'invoice', 'vehicle_id' => 3, 'urgency' => 'low',
                'question' => "TALLERES HNOS. GARCÍA\nFactura 2026/0412, 03/10/2026\nCitroën Berlingo 2156 GFB, 214.650 km\nAceite 5W30 (4,5 l) ........ 38,70\nFiltro de aceite ............ 9,80\nMano de obra (0,5 h) ....... 22,50\nIVA 21 % ................... 14,91\nTOTAL ...................... 85,91 €",
                'summary' => 'Es una factura de mantenimiento rutinario: cambio de aceite y filtro.',
                'causes' => [],
                'advice' => 'Si aceptas la entrada, el aviso de aceite del Citroën Berlingo quedará al día y su vitalidad subirá.',
                'proposals' => [
                    ['kind' => 'ritual', 'task' => 'oil', 'title' => 'Cambio de aceite y filtro', 'date' => '2026-10-03', 'km' => 214650, 'cost' => 85.91, 'detail' => 'Talleres Hnos. García'],
                    ['kind' => 'reminder', 'task' => 'oil', 'title' => 'Próximo cambio de aceite', 'date' => null, 'km' => 229650, 'cost' => null, 'detail' => 'Aviso por kilómetros'],
                ],
            ],
        ],
        'history' => [
            ['date' => '2026-09-02', 'vehicle_id' => 1, 'question' => 'Se enciende un testigo naranja con forma de motor.', 'urgency' => 'medium'],
            ['date' => '2026-06-18', 'vehicle_id' => 2, 'question' => 'Ruido en la cadena al acelerar.', 'urgency' => 'low'],
        ],
    ],
];
