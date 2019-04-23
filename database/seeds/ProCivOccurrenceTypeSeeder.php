<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\ProCivOccurrenceSpecies;
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
        $species = require 'data/prociv_occurrence_types.php';

        foreach ($species as $code => $types) {
            $parent = ProCivOccurrenceSpecies::where('code', $code)->first();

            foreach ($types as $attributes) {
                factory(ProCivOccurrenceType::class)->create(\array_merge($attributes, [
                    'species_id' => $parent->id,
                ]));
            }
        }
    }
}
