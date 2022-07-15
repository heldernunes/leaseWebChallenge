<?php

namespace App\Tests\Unit\Controller;

use App\Controller\IndexController;
use App\Model\MigrationProductModel;
use App\Service\MigrationService;
use App\Tests\Helper\Faker;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * @coversDefaultClass \App\Controller\IndexController
 */
class IndexControllerTest extends TestCase
{
    protected $victim;
    protected $migrationService;

    protected function setUp()
    {
        $this->migrationService = $this->createMock(MigrationService::class);
    }

    /**
     * @covers ::migrate
     */
    public function testMigrate()
    {
        $this->victim = new IndexController();
        $this->assertInstanceOf(IndexController::class, $this->victim);

        $response = $this->mockServiceResponse();
        $this->migrationService->expects($this->once())
            ->method('migrateCsv')
            ->willReturn($response);

        $result = $this->victim->migrate($this->migrationService);
        $this->assertEquals((new JsonResponse($response))->getContent(), $result->getContent());
    }

    protected function mockServiceResponse(): array
    {
        return [
            [
                "Model" => Faker::text(),
                "RAM" => Faker::text(),
                "HDD" => Faker::text(),
                "Location" => Faker::text(),
                "Price" => Faker::text(),
            ],
            [
                "Model" => Faker::text(),
                "RAM" => Faker::text(),
                "HDD" => Faker::text(),
                "Location" => Faker::text(),
                "Price" => Faker::text(),
            ]
        ];
    }

    /**
     * @dataProvider indexProvider
     * @covers ::index
     * @covers ::getRoutes
     */
    public function testIndex($routes)
    {
        $routeCollection = $this->getRouteCollection($routes);

        $containerMock = $this->createMock(ContainerInterface::class);
        $routerMock = $this->createMock(Router::class);
        $routerMock
            ->expects($this->any())
            ->method('getRouteCollection')
            ->willReturn($routeCollection);
        $containerMock
            ->expects($this->any())
            ->method('get')
            ->with('router')
            ->willReturn($routerMock);

        $controller = new IndexController();
        $controller->setContainer($containerMock);

        $response = $controller->index();
        $responseContent = json_decode($response->getContent());

        $this->assertObjectHasAttribute('routes', $responseContent);
        $this->assertSame($routes, $responseContent->routes);
    }

    public function indexProvider(): array
    {
        return [
            'dev environment' => [
                'route' => ['/someRoute', '/someOtherRoute'],
            ],
        ];
    }

    private function getRouteCollection($routes)
    {
        $mockRouteObjects = [];
        foreach ($routes as $route) {
            $mockRoute = $this->createMock(Route::class);
            $mockRoute->expects($this->any())->method('getPath')->willReturn($route);
            $mockRouteObjects[] = $mockRoute;
        }

        $mockRouteCollection = $this->createMock(RouteCollection::class);
        $mockRouteCollection
            ->expects($this->any())
            ->method('all')
            ->willReturn($mockRouteObjects);

        return $mockRouteCollection;
    }
}
