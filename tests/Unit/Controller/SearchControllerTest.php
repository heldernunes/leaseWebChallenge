<?php

namespace App\Tests\Unit\Controller;

use App\Controller\SearchController;
use App\Model\ProductModel;
use App\Model\Request\RequestModelInterface;
use App\Model\Request\SearchReqModel;
use App\Model\Response\SearchProductResponseModel;
use App\Service\SearchService;
use App\Transformer\BaseTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Controller\SearchController
 */
class SearchControllerTest extends TestCase
{
    private $victim;
    private $searchService;

    protected function setUp(): void
    {
        $this->victim = new SearchController();
        $this->searchService = $this->createMock(SearchService::class);
    }

    /**
     * @dataProvider searchProductDataProvider
     *
     * @covers ::search
     */
    public function testSearch(
        RequestModelInterface $requestModel,
        SearchProductResponseModel $searchProductRespModel
    ) {
        $transformer = new BaseTransformer();
        $transformer->setContentType("application/json");

        $expected = $transformer->apply($searchProductRespModel);

        $this->searchService->expects($this->once())
            ->method('getSearchProduct')
            ->with($requestModel->toArray())
            ->willReturn($expected);

        $result = $this->victim->search($requestModel, $this->searchService);

        $this->assertEquals($expected->getContent(), $result->getContent());
    }

    public function searchProductDataProvider(): array
    {
        return [
            [
                'reqModel' => new SearchReqModel(
                    [
                        'storage' => [
                            [
                                'start' => '250GB',
                                'end' => '8TB',
                            ]
                        ],
                        'ram' => [
                            ['value' => '4GB'],
                            ['value' => '8GB'],
                            ['value' => '24GB'],
                        ],
                        'hdd' => 'SATA',
                        'location' => 'AmsterdamAMS-01',
                    ]
                ),
                'searchProductRespModel' => new SearchProductResponseModel(
                    [
                        [
                            "productId" => 9,
                            "model" => "HP DL120G7Intel G850",
                            "ram" => "4GBDDR3",
                            "hdd" => "4x1TBSATA2",
                            "location" => "AmsterdamAMS-01",
                            "price" => 39.99,
                            "currency" => "EUR",
                            "currencySymbol" => "€",
                            "discType" => "SATA",
                            "memorySize" => "4",
                            "memoryScale" => "GB",
                            "discSize" => "4",
                            "discScale" => "TB"
                        ]
                    ]
                )
            ],
        ];
    }
}
