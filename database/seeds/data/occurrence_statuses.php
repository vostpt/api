<?php

declare(strict_types=1);

use VOSTPT\Models\OccurrenceStatus;

return [
    [
        'code' => OccurrenceStatus::FALSE_ALERT,
        'name' => 'Falso Alerta',
    ],
    [
        'code' => OccurrenceStatus::SURVEILLANCE,
        'name' => 'Vigilância',
    ],
    [
        'code' => OccurrenceStatus::DISPATCH,
        'name' => 'Despacho',
    ],
    [
        'code' => OccurrenceStatus::FIRST_ALERT_DISPATCH,
        'name' => 'Despacho de 1º Alerta',
    ],
    [
        'code' => OccurrenceStatus::ONGOING,
        'name' => 'Em Curso',
    ],
    [
        'code' => OccurrenceStatus::ARRIVAL_AT_TO,
        'name' => 'Chegada ao TO',
    ],
    [
        'code' => OccurrenceStatus::RESOLVING,
        'name' => 'Em Resolução',
    ],
    [
        'code' => OccurrenceStatus::CONCLUSION,
        'name' => 'Conclusão',
    ],
    [
        'code' => OccurrenceStatus::CLOSED,
        'name' => 'Encerrada',
    ],
    [
        'code' => OccurrenceStatus::CLOSED_BY_VOST,
        'name' => 'Encerrada pela VOST',
    ],
];
