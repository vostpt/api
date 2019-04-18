<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\ProCivOccurrenceStatus;

class ProCivOccurrenceStatusSeeder extends Seeder
{
    /**
     * Seed the prociv_occurrence_statuses table.
     *
     * @return void
     */
    public function run(): void
    {
        $occurrenceStatuses = require 'data/prociv_occurrence_statuses.php';

        foreach ($occurrenceStatuses as $occurrenceStatus) {
            factory(ProCivOccurrenceStatus::class)->create($occurrenceStatus);
        }
    }
}
