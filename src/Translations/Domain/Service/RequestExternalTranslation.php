<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderRequest;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderResponse;
use Iterator;

final class RequestExternalTranslation
{
    private const MIN_IMPLEMENTATIONS = 2;

    /** @var TranslationProvider[] */
    private array $translationProviders;

    /** @param Iterator<int, TranslationProvider> $translationProviders */
    public function __construct(iterable $translationProviders)
    {
        $this->translationProviders = iterator_to_array($translationProviders);
    }

    public function __invoke(TranslationProviderRequest $translation): TranslationProviderResponse
    {
        if (
            $numImplementations = count($this->translationProviders) < self::MIN_IMPLEMENTATIONS
        ) {
            throw new MissingProviderException(
                self::MIN_IMPLEMENTATIONS - $numImplementations
            );
        }

        $result = new TranslationProviderResponse();
        $provider = array_shift($this->translationProviders);
        while (empty($result->translatedText()) && !empty($provider)) {
            $result = $provider->translate($translation);
            $provider = array_shift($this->translationProviders);
        }

        return $result;
    }
}
