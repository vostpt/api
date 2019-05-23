<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\OccurrenceFamily;
use VOSTPT\Models\OccurrenceSpecies;

class OccurrenceSpeciesSeeder extends Seeder
{
    /**
     * Seed the occurrence_species table.
     *
     * @return void
     */
    public function run(): void
    {
        $families = require 'data/occurrence_species.php';

        foreach ($families as $code => $species) {
            $parent = OccurrenceFamily::where('code', $code)->first();

            foreach ($species as $attributes) {
                factory(OccurrenceSpecies::class)->create(\array_merge($attributes, [
                    'family_id' => $parent->id,
                ]));
            }
        }
    }
}
