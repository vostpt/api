<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients;

class ProCivWebsiteServiceClient extends ServiceClient implements Contracts\ProCivWebsiteServiceClient
{
    /**
     * {@inheritDoc}
     */
    public function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getMainOccurrences(): array
    {
        $response = $this->post('_vti_bin/ARM.ANPC.UI/ANPC_SituacaoOperacional.svc/GetMainOccurrences', [
            'allData' => true,
        ]);

        return $response['GetMainOccurrencesResult']['ArrayInfo'][0] ?? [
            'Data'  => [],
            'Total' => 0,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getOccurrenceHistory(): array
    {
        $response = $this->post('_vti_bin/ARM.ANPC.UI/ANPC_SituacaoOperacional.svc/GetHistoryOccurrencesByLocation', [
            'allData' => true,
        ]);

        return $response['GetHistoryOccurrencesByLocationResult']['ArrayInfo'][0] ?? [
            'Data'  => [],
            'Total' => 0,
        ];
    }
}
