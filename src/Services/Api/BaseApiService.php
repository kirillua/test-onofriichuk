<?php

namespace App\Services\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class BaseApiService
{
    protected string $hostName;

    public function __construct(private Client $client = new Client())
    {
    }

    protected function makeRequest(string $httpMethod, string $url, array $options = []): PromiseInterface|ResponseInterface
    {
        return match (strtolower($httpMethod)) {
            'get' => $this->client->get($this->hostName . $url, $options),
            'put' => $this->client->put($this->hostName . $url, $options),
            'post' => $this->client->post($this->hostName . $url, $options),
            'patch' => $this->client->patch($this->hostName . $url, $options),
            'delete' => $this->client->delete($this->hostName . $url, $options),
        };
    }

    protected function decode(ResponseInterface $response, bool $asArray = true)
    {
        return json_decode($response->getBody()->getContents(), $asArray);
    }
}