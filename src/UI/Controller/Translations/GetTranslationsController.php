<?php

declare(strict_types=1);

namespace App\UI\Controller\Translations;

use App\Translations\Application\Query\GetTranslations\GetTranslationsQuery;
use App\UI\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetTranslationsController extends BaseController
{
    public function __invoke(): Response
    {
        $response = $this->ask(new GetTranslationsQuery());
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
