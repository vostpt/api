<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceController;

use VOSTPT\Models\County;
use VOSTPT\Models\District;
use VOSTPT\Models\Event;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\Parish;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexOccurrencesDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::index'));

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(415);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 415,
                    'detail' => 'Wrong media type',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToIndexOccurrencesDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('occurrences::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'search' => '',
            'exact'  => 'yes',
            'events' => [
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
            'sort'  => 'id',
            'order' => 'up',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The page.number must be an integer.',
                    'meta'   => [
                        'field' => 'page.number',
                    ],
                ],
                [
                    'detail' => 'The page.size must be an integer.',
                    'meta'   => [
                        'field' => 'page.size',
                    ],
                ],
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
                    'detail' => 'The selected events.0 is invalid.',
                    'meta'   => [
                        'field' => 'events.0',
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
    public function itSuccessfullyIndexesOccurrences(): void
    {
        $event    = factory(Event::class)->create();
        $district = factory(District::class)->create();
        $county   = factory(County::class)->create([
            'district_id' => $district->getKey(),
        ]);
        $parish = factory(Parish::class)->create([
            'county_id' => $county->getKey(),
        ]);

        $occurrences = factory(Occurrence::class, 20)->make([
            'event_id'  => $event->getKey(),
            'parish_id' => $parish->getKey(),
        ]);

        factory(ProCivOccurrence::class, 20)->create()->each(function (ProCivOccurrence $proCivOccurrence, $index) use ($occurrences) {
            $proCivOccurrence->occurrence()->save($occurrences[$index]);
        });

        $response = $this->json('GET', route('occurrences::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'events' => [
                $event->getKey(),
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
            'search' => '0 1 2 3 4 5 6 7 8 9',
            'sort'   => 'locality',
            'order'  => 'asc',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'locality',
                        'latitude',
                        'longitude',
                        'started_at',
                        'ended_at',
                        'created_at',
                        'updated_at',
                    ],
                    'links' => [
                        'self',
                    ],
                ],
            ],
            'meta' => [
                'items',
                'total',
            ],
        ]);
    }
}
