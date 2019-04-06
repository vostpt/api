<?php

declare(strict_types=1);

namespace VOSTPT\API\ServiceClients;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ServiceClient implements Contracts\ServiceClient
{
    /**
     * HTTP client.
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Service hostname.
     *
     * @var string
     */
    protected $hostname;

    /**
     * Service client constructor.
     *
     * @param HttpClient $httpClient
     * @param string     $hostname
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(HttpClient $httpClient, string $hostname)
    {
        if (filter_var($hostname, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Invalid hostname: '.$hostname);
        }

        $this->httpClient = $httpClient;
        $this->hostname   = trim($hostname, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function getHostname(string $path = null): string
    {
        // Make sure trailing slashes are removed
        return sprintf('%s/%s', $this->hostname, trim($path, '/'));
    }

    /**
     * Parse the service response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return mixed
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $code = $response->getStatusCode();

        if ($code >= 400) {
            throw new HttpException($code, $response->getReasonPhrase());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Check if the request requires body data.
     *
     * @param string $method
     * @return bool
     */
    protected function requiresBody(string $method): bool
    {
        return \in_array(strtoupper($method), ['POST', 'PUT'], true);
    }

    /**
     * {@inheritDoc}
     */
    public function request(string $method, string $path, array $parameters = [], array $headers = [])
    {
        $url = $this->buildUrl($path, $parameters, $method);

        // POST/PUT parameters are sent in the body
        if ($this->requiresBody($method)) {
            $body = json_encode($parameters);
        }

        $request = new Request($method, $url, $headers, $body ?? null);

        $response = $this->httpClient->send($request);

        return $this->parseResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $path, array $parameters = [], array $headers = [])
    {
        return $this->request('GET', $path, $parameters, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post(string $path, array $parameters = [], array $headers = [])
    {
        return $this->request('POST', $path, $parameters, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $path, array $parameters = [], array $headers = [])
    {
        return $this->request('PUT', $path, $parameters, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $path, array $parameters = [], array $headers = [])
    {
        return $this->request('DELETE', $path, $parameters, $headers);
    }
}
