<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyHttpClient
{
    public function __construct(
        private ?HttpClientInterface $client = null
    ) {
        $this->client = new RetryableHttpClient(HttpClient::create());
    }

    /**
     * @param array<string, string|array<string, string>> $httpOptions
     */
    public function submit(string $url, array $httpOptions): ResponseInterface
    {
        return $this->client->request(
            'POST',
            $url,
            (new HttpOptions())
                ->setBaseUri($httpOptions['base_uri'])
                ->setHeaders($httpOptions['headers'])
                ->setJson($httpOptions['json'])
                ->toArray()
        );
    }
}
