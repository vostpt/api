<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

use GuzzleHttp\Psr7\Response;
use Http\Client\HttpClient;
use Http\Mock\Client as MockHttpClient;
use Psr\Http\Message\ResponseInterface;

trait HttpClientMocker
{
    /**
     * @param ResponseInterface[] $responses
     *
     * @return HttpClient
     */
    protected function createHttpClient(ResponseInterface ...$responses): HttpClient
    {
        $httpClient = new MockHttpClient();

        foreach ($responses as $response) {
            $httpClient->addResponse($response);
        }

        return $httpClient;
    }

    /**
     * @param string $path
     * @param int    $status
     * @param array  $headers
     *
     * @return ResponseInterface
     */
    protected function createHttpResponse(string $path = null, int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($status, $headers, $path ? \file_get_contents(base_path($path)) : null);
    }
}
