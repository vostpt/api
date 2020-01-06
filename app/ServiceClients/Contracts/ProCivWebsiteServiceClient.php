<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients\Contracts;

interface ProCivWebsiteServiceClient extends ServiceClient
{
    /**
     * Get main occurrences.
     *
     * @return array
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getMainOccurrences(): array;

    /**
     * Get occurrence history.
     *
     * @return array
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getOccurrenceHistory(): array;
}
