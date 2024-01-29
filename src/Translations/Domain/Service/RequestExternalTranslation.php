<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderRequest;
use App\Translations\Domain\ValueObject\TranslationProvider\TranslationProviderResponse;
use IteratorAggregate;

final class RequestExternalTranslation
{
    private const MIN_IMPLEMENTATIONS = 2;

    /** @var TranslationProvider[] */
    private array $fallbackTranslationProviders;

    /** @param IteratorAggregate<int, TranslationProvider> $fallbackTranslationProviders */
    public function __construct(
        private readonly TranslationProvider $primaryTranslationProvider,
        iterable $fallbackTranslationProviders
    ) {
        $this->fallbackTranslationProviders = iterator_to_array($fallbackTranslationProviders);
    }

    public function __invoke(TranslationProviderRequest $translation): TranslationProviderResponse
    {
        if (
            $numImplementations = count($this->fallbackTranslationProviders) < self::MIN_IMPLEMENTATIONS
        ) {
            throw new MissingProviderException(
                self::MIN_IMPLEMENTATIONS - $numImplementations
            );
        }

        $result = $this->primaryTranslationProvider->translate($translation);
        $fallbackProvider = array_shift($this->fallbackTranslationProviders);
        while (empty($result->translatedText()) && !empty($fallbackProvider)) {
            $result = $fallbackProvider->translate($translation);
            $fallbackProvider = array_shift($this->fallbackTranslationProviders);
        }

        return $result;
    }
}
