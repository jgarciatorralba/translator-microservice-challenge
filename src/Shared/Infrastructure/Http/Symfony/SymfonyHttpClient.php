<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use Exception;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class SymfonyHttpClient
{
    public function __construct(
        private readonly HttpClientInterface $client
    ) {
    }

    /**
     * @param array<string, string|array<string>|array<string, string>> $httpOptions
     * @return array<string, string|null>
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
            $result['output'] = $response->getContent();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
