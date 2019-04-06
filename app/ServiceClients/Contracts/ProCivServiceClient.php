<?php

declare(strict_types=1);

namespace VOSTPT\API\ServiceClients\Contracts;

interface ProCivServiceClient extends ServiceClient
{
    /**
     * Get main occurrences.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
    public function getMainOccurrences(): array;

    /**
     * Get occurrence history.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
    public function getOccurrenceHistory(): array;
}
