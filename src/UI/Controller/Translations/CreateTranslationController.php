<?php

declare(strict_types=1);

namespace App\UI\Controller\Translations;

use App\Shared\Domain\ValueObject\Uuid;
use App\UI\Controller\BaseController;
use App\UI\Request\Translations\CreateTranslationRequest;
use App\Translations\Application\Command\CreateTranslation\CreateTranslationCommand;
use App\Translations\Domain\ValueObject\StatusEnum;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateTranslationController extends BaseController
{
    public function __invoke(CreateTranslationRequest $request): Response
    {
        $data = $request->payload();

        $id = Uuid::random()->value();

        $this->dispatch(new CreateTranslationCommand(
            id: $id,
            sourceLanguage: $data['sourceLanguage'],
            originalText: $data['originalText'],
            targetLanguage: $data['targetLanguage'],
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        ));

        return new JsonResponse([
            'id' => $id,
            'status' => StatusEnum::QUEUED->value
        ], Response::HTTP_CREATED);
    }
}
