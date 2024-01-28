<?php

declare(strict_types=1);

namespace App\Translations\Domain\Service;

use App\Translations\Domain\Contract\TranslationProvider;
use App\Translations\Domain\Exception\MissingProviderException;
use App\Translations\Domain\ValueObject\TranslationRequest;
use App\Translations\Domain\ValueObject\TranslationResponse;

final class RequestExternalTranslation
{
    private const MIN_IMPLEMENTATIONS = 2;

    /**
     * @param TranslationProvider[] $translationProviders
     */
    public function __construct(
        private array $translationProviders = []
    ) {
        $implementations = array_filter(
            get_declared_classes(),
            fn(string $className) => in_array(
                TranslationProvider::class,
                class_implements($className)
            )
        );

        foreach ($implementations as $implementation) {
            $this->translationProviders[] = new $implementation();
        }
    }

    public function __invoke(TranslationRequest $translation): TranslationResponse
    {
        if (
            $numImplementations = count($this->translationProviders) < self::MIN_IMPLEMENTATIONS
        ) {
            throw new MissingProviderException(
                self::MIN_IMPLEMENTATIONS - $numImplementations
            );
        }

        $result = new TranslationResponse();
        $provider = array_shift($this->translationProviders);
        while (empty($result->translatedText()) && !empty($provider)) {
            $result = $provider->translate($translation);
            $provider = array_shift($this->translationProviders);
        }

        return $result;
    }
}
