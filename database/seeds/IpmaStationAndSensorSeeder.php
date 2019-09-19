<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\County;
use VOSTPT\Models\WeatherSensor;
use VOSTPT\Models\WeatherStation;

class IpmaStationAndSensorSeeder extends Seeder
{
    /**
     * Seed the weather_stations and weather_sensors table.
     *
     * @return void
     */
    public function run(): void
    {
        $sensors = require 'data/ipma_sensors.php';

        foreach ($sensors as $attributes) {
            $county = County::where('code', $attributes['county'])->first();

            $station = WeatherStation::where([
                ['serial', '=', $attributes['station_number']],
                ['entity', '=', 'IPMA'],
            ])->first();

            if ($station === null) {
                $station = factory(WeatherStation::class)->create([
                    'county_id' => $county->getKey(),
                    'entity'    => 'IPMA',
                    'name'      => $attributes['name'],
                    'serial'    => $attributes['station_number'],
                ]);
            }

            factory(WeatherSensor::class)->create([
                'station_id' => $station->getKey(),
                'type'       => $attributes['sensor_type'],
                'latitude'   => $attributes['latitude'],
                'longitude'  => $attributes['longitude'],
                'altitude'   => $attributes['altitude'],
                'started_at' => $attributes['started_at'],
            ]);
        }
    }
}
