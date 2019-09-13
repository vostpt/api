<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients;

class IpmaApiServiceClient extends ServiceClient implements Contracts\IpmaApiServiceClient
{
    /**
     * {@inheritDoc}
     */
    public function getWarnings(): array
    {
        return $this->get('json/warnings_www.json');
    }

    /**
     * {@inheritDoc}
     */
    public function getSurfaceObservations(): array
    {
        $results = $this->get('open-data/observation/meteorology/stations/observations.json');

        // Results come out of order, so we sort them out before returning them
        \ksort($results);

        return $results;
    }
}
