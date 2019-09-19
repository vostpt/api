<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients\Contracts;

interface IpmaApiServiceClient extends ServiceClient
{
    /**
     * Get warnings.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
    public function getWarnings(): array;

    /**
     * Get surface observations.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
    public function getSurfaceObservations(): array;
}
