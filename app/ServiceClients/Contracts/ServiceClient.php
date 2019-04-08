<?php

declare(strict_types=1);

namespace VOSTPT\ServiceClients\Contracts;

interface ServiceClient
{
    /**
     * Build the request URL.
     *
     * @param string $path
     * @param array  $parameters
     * @param string $method
     *
     * @return string
     */
    public function buildUrl(string $path, array $parameters, string $method): string;

    /**
     * Get the service hostname.
     *
     * @param string $path Path to append
     *
     * @return string
     */
    public function getHostname(string $path = null): string;

    /**
     * Perform an HTTP request.
     *
     * @param string $method
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    public function request(string $method, string $path, array $parameters = [], array $headers = []);

    /**
     * Perform an HTTP GET request.
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    public function get(string $path, array $parameters = [], array $headers = []);

    /**
     * Perform an HTTP POST request.
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    public function post(string $path, array $parameters = [], array $headers = []);

    /**
     * Perform an HTTP PUT request.
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    public function put(string $path, array $parameters = [], array $headers = []);

    /**
     * Perform an HTTP DELETE request.
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    public function delete(string $path, array $parameters = [], array $headers = []);
}
