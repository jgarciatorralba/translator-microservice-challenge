<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Symfony;

use App\Shared\Domain\Contract\HttpClient as HttpClientContract;
use App\Shared\Domain\ValueObject\HttpResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpClient\RetryableHttpClient;

final class SymfonyHttpClient implements HttpClientContract
{
    public function __construct(
        private ?HttpClientInterface $client = null
    ) {
        $this->client = new RetryableHttpClient(HttpClient::create());
    }

    /**
     * @param array<string, string|array<string, string>> $httpOptions
     */
    public function submit(string $url, array $httpOptions): HttpResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $url,
                (new HttpOptions())
                    ->setBaseUri($httpOptions['base_uri'])
                    ->setHeaders($httpOptions['headers'])
                    ->setJson($httpOptions['json'])
                    ->toArray()
            );
            $statusCode = $response->getStatusCode();
            $content = $response->getContent();

            return new HttpResponse($statusCode, null, $content);
        } catch (TransportExceptionInterface | DecodingExceptionInterface | HttpExceptionInterface $e) {
            return new HttpResponse(
                $statusCode ?? null,
                $e->getMessage(),
                null
            );
        }
    }
}
