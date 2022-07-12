<?php

namespace App\Controller;

use App\Service\MigrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="app_index")
     */
    public function index(MigrationService $migrationService): JsonResponse
    {
        $result = $migrationService->migrateCsv();

        return new JsonResponse($result);
    }
}
