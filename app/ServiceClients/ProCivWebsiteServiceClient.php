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
            'Content-Type' => 'application/json;charset=utf-8',
            'User-Agent'   => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:71.0) Gecko/20100101 Firefox/71.0',
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
