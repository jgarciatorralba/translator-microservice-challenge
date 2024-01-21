<?php

declare(strict_types=1);

namespace App\UI\Controller\TranslationRequests;

use App\TranslationRequests\Application\Query\GetTranslationRequests\GetTranslationRequestsQuery;
use App\UI\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetTranslationRequestsController extends BaseController
{
    public function __invoke(): Response
    {
        $response = $this->ask(new GetTranslationRequestsQuery());
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
