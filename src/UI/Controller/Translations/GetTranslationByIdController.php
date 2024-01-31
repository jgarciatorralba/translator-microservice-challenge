<?php

declare(strict_types=1);

namespace App\UI\Controller\Translations;

use App\UI\Controller\BaseController;
use App\Translations\Application\Query\GetTranslationById\GetTranslationByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetTranslationByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $response = $this->ask(new GetTranslationByIdQuery($id));

        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
