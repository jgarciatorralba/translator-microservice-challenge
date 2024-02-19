<?php

declare(strict_types=1);

namespace App\UI\Controller\Translations;

use App\Shared\Utils;
use App\Translations\Application\Query\GetTranslations\GetTranslationsQuery;
use App\UI\Controller\BaseController;
use App\UI\Request\Translations\GetTranslationsRequest;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetTranslationsController extends BaseController
{
    public function __invoke(GetTranslationsRequest $request): Response
    {
        $pageSize = $request->get('pageSize');
        $createdAt = Utils::stringToDate($request->get('createdAt') ?? 'now');

        $response = $this->ask(
            new GetTranslationsQuery(
                pageSize: is_numeric($pageSize) ? intval($pageSize) : null,
                createdAt: $createdAt ?? new DateTimeImmutable()
            )
        );
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
