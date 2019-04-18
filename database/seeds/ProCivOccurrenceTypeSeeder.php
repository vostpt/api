<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\ProCivOccurrenceType;

class ProCivOccurrenceTypeSeeder extends Seeder
{
    /**
     * Seed the prociv_occurrence_types table.
     *
     * @return void
     */
    public function run(): void
    {
        $occurrenceTypes = require 'data/prociv_occurrence_types.php';

        foreach ($occurrenceTypes as $occurrenceType) {
            factory(ProCivOccurrenceType::class)->create($occurrenceType);
        }
    }
}
