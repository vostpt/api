<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\District;

class DistrictSeeder extends Seeder
{
    /**
     * Seed the districts table.
     *
     * @return void
     */
    public function run(): void
    {
        $districts = require 'data/districts.php';

        foreach ($districts as $district) {
            factory(District::class)->create($district);
        }
    }
}
