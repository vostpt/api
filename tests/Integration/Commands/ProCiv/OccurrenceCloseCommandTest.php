<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Commands\ProCiv;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\TestCase;

class OccurrenceCloseCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itSuccessfullyClosesOccurrences(): void
    {
        $this->app->instance(LoggerInterface::class, new NullLogger());

        $firstAlertDispatch = factory(OccurrenceStatus::class)->create([
            'code' => OccurrenceStatus::FIRST_ALERT_DISPATCH,
        ]);

        $stalledOccurrence = factory(Occurrence::class)->make([
            'event_id'   => null,
            'status_id'  => $firstAlertDispatch->getKey(),
            'updated_at' => Carbon::yesterday(),
        ]);

        factory(ProCivOccurrence::class)->create()->parent()->save($stalledOccurrence);

        $closedByVost = factory(OccurrenceStatus::class)->create([
            'code' => OccurrenceStatus::CLOSED_BY_VOST,
        ]);

        $this->artisan('prociv:close:occurrences');

        $this->assertDatabaseHas('occurrences', [
            'id'        => $stalledOccurrence->getKey(),
            'status_id' => $closedByVost->getKey(),
        ]);
    }
}
