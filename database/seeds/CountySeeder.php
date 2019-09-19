<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\County;
use VOSTPT\Models\District;

class CountySeeder extends Seeder
{
    /**
     * Seed the counties table.
     *
     * @return void
     */
    public function run(): void
    {
        $districts = require 'data/counties.php';

        foreach ($districts as $districtCode => $counties) {
            $district = District::where('code', $districtCode)->first();

            foreach ($counties as $county) {
                factory(County::class)->create(\array_merge($county, [
                    'district_id' => $district->getKey(),
                ]));
            }
        }
    }
}
