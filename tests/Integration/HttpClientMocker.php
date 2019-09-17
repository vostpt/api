<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait HttpClientMocker
{
    /**
     * @param ResponseInterface[] $responses
     *
     * @return Client
     */
    protected function createHttpClient(ResponseInterface ...$responses): Client
    {
        return new Client([
            'handler' => HandlerStack::create(new MockHandler($responses)),
        ]);
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
