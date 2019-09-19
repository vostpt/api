<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\OccurrenceSpecies;
use VOSTPT\Models\OccurrenceType;

class OccurrenceTypeSeeder extends Seeder
{
    /**
     * Seed the occurrence_types table.
     *
     * @return void
     */
    public function run(): void
    {
        $species = require 'data/occurrence_types.php';

        foreach ($species as $code => $types) {
            $parent = OccurrenceSpecies::where('code', $code)->first();

            foreach ($types as $attributes) {
                factory(OccurrenceType::class)->create(\array_merge($attributes, [
                    'species_id' => $parent->getKey(),
                ]));
            }
        }
    }
}
