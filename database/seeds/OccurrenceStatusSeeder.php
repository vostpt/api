<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\OccurrenceStatus;

class OccurrenceStatusSeeder extends Seeder
{
    /**
     * Seed the occurrence_statuses table.
     *
     * @return void
     */
    public function run(): void
    {
        $occurrenceStatuses = require 'data/occurrence_statuses.php';

        foreach ($occurrenceStatuses as $occurrenceStatus) {
            factory(OccurrenceStatus::class)->create($occurrenceStatus);
        }
    }
}
