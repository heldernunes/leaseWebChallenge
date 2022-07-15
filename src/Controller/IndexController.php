<?php

namespace App\Controller;

use App\Service\MigrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/migrate", name="app_migrate")
     */
    public function migrate(MigrationService $migrationService): JsonResponse
    {
        $result = $migrationService->migrateCsv();

        return new JsonResponse($result);
    }

    /**
     * @Route("/index", name="app_index")
     *
     * @SuppressWarnings(PHPMD.UndefinedVariable)
     */
    public function index(): JsonResponse
    {
        $content['routes'] = $this->getRoutes();

        return new JsonResponse($content);
    }

    protected function getRoutes(): array
    {
        $router = $this->container->get('router');
        return array_map(function ($route) {
            return $route->getPath();
        }, $router->getRouteCollection()->all());
    }
}
