<?php

declare(strict_types=1);

use VOSTPT\Models\ProCivOccurrenceStatus;

return [
    [
        'code' => ProCivOccurrenceStatus::FALSE_ALERT,
        'name' => 'Falso Alerta',
    ],
    [
        'code' => ProCivOccurrenceStatus::SURVEILLANCE,
        'name' => 'Vigilância',
    ],
    [
        'code' => ProCivOccurrenceStatus::DISPATCH,
        'name' => 'Despacho',
    ],
    [
        'code' => ProCivOccurrenceStatus::FIRST_ALERT_DISPATCH,
        'name' => 'Despacho de 1º Alerta',
    ],
    [
        'code' => ProCivOccurrenceStatus::ONGOING,
        'name' => 'Em Curso',
    ],
    [
        'code' => ProCivOccurrenceStatus::ARRIVAL_AT_TO,
        'name' => 'Chegada ao TO',
    ],
    [
        'code' => ProCivOccurrenceStatus::RESOLVING,
        'name' => 'Em Resolução',
    ],
    [
        'code' => ProCivOccurrenceStatus::CONCLUSION,
        'name' => 'Conclusão',
    ],
    [
        'code' => ProCivOccurrenceStatus::CLOSED,
        'name' => 'Encerrada',
    ],
    [
        'code' => ProCivOccurrenceStatus::CLOSED_BY_VOST,
        'name' => 'Encerrada pela VOST',
    ],
];
