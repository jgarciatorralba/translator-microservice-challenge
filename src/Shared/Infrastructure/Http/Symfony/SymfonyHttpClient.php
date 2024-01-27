<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyHttpClient
{
    public function __construct(
        private ?HttpClientInterface $client = null
    ) {
        $this->client = new RetryableHttpClient(HttpClient::create());
    }

    /**
     * @param array<string, string|array<string, string>> $httpOptions
     * @return array<string, string|array<string, mixed>|null>
     */
    public function submit(string $url, array $httpOptions): array
    {
        $result = [
            'output' => null,
            'error' => null
        ];

        $this->client->withOptions(
            (new HttpOptions())
                ->setBaseUri($httpOptions['base_uri'])
                ->setHeaders($httpOptions['headers'])
                ->setJson($httpOptions['json'])
                ->toArray()
        );

        try {
            $response = $this->client->request('POST', $url);
            $result['output'] = $response->toArray();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
