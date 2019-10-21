<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceReportController;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\County;
use VOSTPT\Models\District;
use VOSTPT\Models\Event;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Models\OccurrenceType;
use VOSTPT\Models\Parish;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\TestCase;

class GenerateReportEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToGenerateOccurrenceReportDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::reports::generate'), [], static::INVALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(415);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 415,
                    'detail' => 'Unsupported media type',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToGenerateOccurrenceReportDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('occurrences::reports::generate'), [], static::INVALID_ACCEPT_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(406);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 406,
                    'detail' => 'Not acceptable',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToGenerateOccurrenceReportDueToValidation(): void
    {
        $response = $this->json('GET', route('occurrences::reports::generate'), [
            'ids' => [
                123,
            ],
            'search' => '',
            'exact'  => 'yes',
            'events' => [
                1,
            ],
            'types' => [
                1,
            ],
            'statuses' => [
                1,
            ],
            'districts' => [
                1,
            ],
            'counties' => [
                1,
            ],
            'parishes' => [
                1,
            ],
            'started_at' => '2000-12-31',
            'ended_at'   => '2000-01-01',
            'sort'       => 'id',
            'order'      => 'up',
        ], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The search must be a string.',
                    'meta'   => [
                        'field' => 'search',
                    ],
                ],
                [
                    'detail' => 'The exact field must be true or false.',
                    'meta'   => [
                        'field' => 'exact',
                    ],
                ],
                [
                    'detail' => 'The started at must be a date before or equal to ended at.',
                    'meta'   => [
                        'field' => 'started_at',
                    ],
                ],
                [
                    'detail' => 'The ended at must be a date after or equal to started at.',
                    'meta'   => [
                        'field' => 'ended_at',
                    ],
                ],
                [
                    'detail' => 'The selected sort is invalid.',
                    'meta'   => [
                        'field' => 'sort',
                    ],
                ],
                [
                    'detail' => 'The selected order is invalid.',
                    'meta'   => [
                        'field' => 'order',
                    ],
                ],
                [
                    'detail' => 'The selected ids.0 is invalid.',
                    'meta'   => [
                        'field' => 'ids.0',
                    ],
                ],
                [
                    'detail' => 'The selected events.0 is invalid.',
                    'meta'   => [
                        'field' => 'events.0',
                    ],
                ],
                [
                    'detail' => 'The selected types.0 is invalid.',
                    'meta'   => [
                        'field' => 'types.0',
                    ],
                ],
                [
                    'detail' => 'The selected statuses.0 is invalid.',
                    'meta'   => [
                        'field' => 'statuses.0',
                    ],
                ],
                [
                    'detail' => 'The selected districts.0 is invalid.',
                    'meta'   => [
                        'field' => 'districts.0',
                    ],
                ],
                [
                    'detail' => 'The selected counties.0 is invalid.',
                    'meta'   => [
                        'field' => 'counties.0',
                    ],
                ],
                [
                    'detail' => 'The selected parishes.0 is invalid.',
                    'meta'   => [
                        'field' => 'parishes.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyGeneratesOccurrenceReport(): void
    {
        $event    = factory(Event::class)->create();
        $type     = factory(OccurrenceType::class)->create();
        $status   = factory(OccurrenceStatus::class)->create();
        $district = factory(District::class)->create();
        $county   = factory(County::class)->create([
            'district_id' => $district->getKey(),
        ]);
        $parish = factory(Parish::class)->create([
            'county_id' => $county->getKey(),
        ]);

        $yesterday = Carbon::yesterday();
        $today     = Carbon::now();

        $occurrences = factory(Occurrence::class, 20)->make([
            'event_id'   => $event->getKey(),
            'type_id'    => $type->getKey(),
            'status_id'  => $status->getKey(),
            'parish_id'  => $parish->getKey(),
            'started_at' => $yesterday,
            'ended_at'   => $today,
        ]);

        factory(ProCivOccurrence::class, 20)->create()->each(function (ProCivOccurrence $proCivOccurrence, $index) use ($occurrences) {
            $proCivOccurrence->parent()->save($occurrences[$index]);
        });

        $response = $this->json('GET', route('occurrences::reports::generate'), [
            'events' => [
                $event->getKey(),
            ],
            'types' => [
                $type->getKey(),
            ],
            'statuses' => [
                $status->getKey(),
            ],
            'districts' => [
                $district->getKey(),
            ],
            'counties' => [
                $county->getKey(),
            ],
            'parishes' => [
                $parish->getKey(),
            ],
            'started_at' => $yesterday->toDateString(),
            'ended_at'   => $today->toDateString(),
            'ids'        => \range(1, 20),
            'search'     => '0 1 2 3 4 5 6 7 8 9',
            'sort'       => 'locality',
            'order'      => 'asc',
        ], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(202);
        $response->assertJsonStructure([
            'meta' => [
                'report' => [
                    'signature',
                    'ready',
                ],
            ],
        ]);
    }
}
