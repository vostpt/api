<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\County;
use VOSTPT\Models\Parish;

class ParishSeeder extends Seeder
{
    /**
     * Seed the parishes table.
     *
     * @return void
     */
    public function run(): void
    {
        $counties = require 'data/parishes.php';

        foreach ($counties as $countyCode => $parishes) {
            $county = County::where('code', $countyCode)->first();

            foreach ($parishes as $parish) {
                factory(Parish::class)->create(\array_merge($parish, [
                    'county_id' => $county->id,
                ]));
            }
        }
    }
}
