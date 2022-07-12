<?php

namespace App\Tests\Unit\Controller;

use App\Controller\IndexController;
use App\Model\MigrationProductModel;
use App\Service\MigrationService;
use App\Tests\Helper\Faker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @covers ::index
     */
    public function testIndex()
    {
        $this->victim = new IndexController();
        $this->assertInstanceOf(IndexController::class, $this->victim);

        $response = $this->mockServiceResponse();
        $this->migrationService->expects($this->once())
            ->method('migrateCsv')
            ->willReturn($response);

        $result = $this->victim->index($this->migrationService);
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
}
