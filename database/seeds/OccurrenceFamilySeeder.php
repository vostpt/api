<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\OccurrenceFamily;

class OccurrenceFamilySeeder extends Seeder
{
    /**
     * Seed the occurrence_families table.
     *
     * @return void
     */
    public function run(): void
    {
        $families = require 'data/occurrence_families.php';

        foreach ($families as $attributes) {
            factory(OccurrenceFamily::class)->create($attributes);
        }
    }
}
