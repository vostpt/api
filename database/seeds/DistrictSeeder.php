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
        $districts = [
            [
                'code' => '010000',
                'name' => 'Aveiro',
            ],
            [
                'code' => '020000',
                'name' => 'Beja',
            ],
            [
                'code' => '030000',
                'name' => 'Braga',
            ],
            [
                'code' => '040000',
                'name' => 'Bragança',
            ],
            [
                'code' => '050000',
                'name' => 'Castelo Branco',
            ],
            [
                'code' => '060000',
                'name' => 'Coimbra',
            ],
            [
                'code' => '070000',
                'name' => 'Évora',
            ],
            [
                'code' => '080000',
                'name' => 'Faro',
            ],
            [
                'code' => '090000',
                'name' => 'Guarda',
            ],
            [
                'code' => '100000',
                'name' => 'Leiria',
            ],
            [
                'code' => '110000',
                'name' => 'Lisboa',
            ],
            [
                'code' => '120000',
                'name' => 'Portalegre',
            ],
            [
                'code' => '130000',
                'name' => 'Porto',
            ],
            [
                'code' => '140000',
                'name' => 'Santarém',
            ],
            [
                'code' => '150000',
                'name' => 'Setúbal',
            ],
            [
                'code' => '160000',
                'name' => 'Viana do Castelo',
            ],
            [
                'code' => '170000',
                'name' => 'Vila Real',
            ],
            [
                'code' => '180000',
                'name' => 'Viseu',
            ],
            [
                'code' => '300000',
                'name' => 'Madeira',
            ],
            [
                'code' => '400000',
                'name' => 'Açores',
            ],
        ];

        foreach ($districts as $district) {
            factory(District::class)->create($district);
        }
    }
}
