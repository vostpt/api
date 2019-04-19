<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use VOSTPT\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Seed the event_types table.
     *
     * @return void
     */
    public function run(): void
    {
        $eventTypes = require 'data/event_types.php';

        foreach ($eventTypes as $attributes) {
            factory(EventType::class)->create($attributes);
        }
    }
}
