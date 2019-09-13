<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(CountySeeder::class);
        $this->call(ParishSeeder::class);
        $this->call(AcronymSeeder::class);
        $this->call(EventTypeSeeder::class);
        $this->call(OccurrenceStatusSeeder::class);
        $this->call(OccurrenceFamilySeeder::class);
        $this->call(OccurrenceSpeciesSeeder::class);
        $this->call(OccurrenceTypeSeeder::class);
        $this->call(IpmaStationAndSensorSeeder::class);
    }
}
