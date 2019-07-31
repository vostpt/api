<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IpmaServiceClient extends ServiceClient implements Contracts\IpmaServiceClient
{
    /**
     * {@inheritDoc}
     */
    public function buildUrl(string $path, array $parameters, string $method): string
    {
        return $this->getHostname($path);
    }

    /**
     * {@inheritDoc}
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $code = $response->getStatusCode();

        if ($code >= 400) {
            throw new HttpException($code, $response->getReasonPhrase());
        }

        $matches = [];

        if (\preg_match('/var result_warnings \= (?P<json>.*) ;/', $response->getBody()->getContents(), $matches) !== 1) {
            return [];
        }

        return \json_decode($matches['json'], true);
    }

    /**
     * {@inheritDoc}
     */
    public function getWarnings(): array
    {
        return $this->get('/pt/index.html');
    }
}
