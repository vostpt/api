<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\ProCivOccurrenceFamily;
use VOSTPT\Models\ProCivOccurrenceSpecies;

class ProCivOccurrenceSpeciesSeeder extends Seeder
{
    /**
     * Seed the prociv_occurrence_species table.
     *
     * @return void
     */
    public function run(): void
    {
        $families = require 'data/prociv_occurrence_species.php';

        foreach ($families as $code => $species) {
            $parent = ProCivOccurrenceFamily::where('code', $code)->first();

            foreach ($species as $attributes) {
                factory(ProCivOccurrenceSpecies::class)->create(\array_merge($attributes, [
                    'family_id' => $parent->id,
                ]));
            }
        }
    }
}
