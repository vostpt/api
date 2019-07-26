<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients\Contracts;

interface IpmaServiceClient extends ServiceClient
{
    /**
     * Get warnings.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array
     */
    public function getWarnings(): array;
}
