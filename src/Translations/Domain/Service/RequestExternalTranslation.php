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
    private array $fallbackProviders;

    /** @param IteratorAggregate<int, TranslationProvider> $translationProviders */
    public function __construct(
        private readonly TranslationProvider $primaryTranslationProvider,
        iterable $translationProviders
    ) {
        $implementations = iterator_to_array($translationProviders);
        if (count($implementations) < self::MIN_IMPLEMENTATIONS) {
            throw new MissingProviderException(
                self::MIN_IMPLEMENTATIONS - count($implementations)
            );
        }

        $this->fallbackProviders = array_filter(
            $implementations,
            fn(TranslationProvider $implementation) =>
                get_class($implementation) !== get_class($this->primaryTranslationProvider)
        );
    }

    public function __invoke(TranslationProviderRequest $translation): TranslationProviderResponse
    {
        $result = $this->primaryTranslationProvider->translate($translation);

        while (empty($result->translatedText()) && current($this->fallbackProviders)) {
            $result = current($this->fallbackProviders)->translate($translation);
            if (empty($result->translatedText())) {
                next($this->fallbackProviders);
            }
        }

        return $result;
    }
}
