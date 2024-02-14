<?php

declare(strict_types=1);

namespace App\UI\Controller\Translations;

use App\Translations\Application\Query\GetTranslations\GetTranslationsQuery;
use App\UI\Controller\BaseController;
use App\UI\Request\Translations\GetTranslationsRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetTranslationsController extends BaseController
{
    public function __invoke(GetTranslationsRequest $request): Response
    {
        $page = $request->get('page');
        $size = $request->get('size');

        $response = $this->ask(
            new GetTranslationsQuery(
                page: is_numeric($page) ? intval($page) : null,
                size: is_numeric($size) ? intval($size) : null
            )
        );
        return new JsonResponse($response->data(), Response::HTTP_OK);
    }
}
