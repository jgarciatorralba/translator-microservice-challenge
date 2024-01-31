<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\Translation;
use App\Translations\Domain\ValueObject\TranslationProviderResponse;
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

    public function __invoke(Translation $translation): TranslationProviderResponse
    {
        $response = $this->primaryTranslationProvider->translate($translation);

        while (
            !$response->isFromBadRequest()
            && empty($response->translatedText())
            && current($this->fallbackProviders)
        ) {
            $response = current($this->fallbackProviders)->translate($translation);
            if (empty($response->translatedText())) {
                next($this->fallbackProviders);
            }
        }

        return $response;
    }
}
