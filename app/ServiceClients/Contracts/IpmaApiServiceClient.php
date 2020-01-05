<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients\Contracts;

interface IpmaApiServiceClient extends ServiceClient
{
    /**
     * Get warnings.
     *
     * @return array
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getWarnings(): array;

    /**
     * Get surface observations.
     *
     * @return array
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getSurfaceObservations(): array;
}
