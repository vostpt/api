<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\ProCivOccurrenceFamily;

class ProCivOccurrenceFamilySeeder extends Seeder
{
    /**
     * Seed the prociv_occurrence_families table.
     *
     * @return void
     */
    public function run(): void
    {
        $families = require 'data/prociv_occurrence_families.php';

        foreach ($families as $attributes) {
            factory(ProCivOccurrenceFamily::class)->create($attributes);
        }
    }
}
