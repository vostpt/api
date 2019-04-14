<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\Acronym;

class AcronymSeeder extends Seeder
{
    /**
     * Seed the acronyms table.
     *
     * @return void
     */
    public function run(): void
    {
        $acronyms = require 'data/acronyms.php';

        foreach ($acronyms as $acronym) {
            factory(Acronym::class)->create($acronym);
        }
    }
}
