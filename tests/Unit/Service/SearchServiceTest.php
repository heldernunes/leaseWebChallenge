<?php

namespace App\Tests\Unit\Service;

use App\Controller\SearchController;
use App\Model\ProductModel;
use App\Model\Request\RamCollectionModel;
use App\Model\Request\RamModel;
use App\Model\Request\RequestModelInterface;
use App\Model\Request\SearchReqModel;
use App\Model\Request\StorageModel;
use App\Model\Response\SearchProductResponseModel;
use App\Repository\SearchRepository;
use App\Service\SearchService;
use App\Transformer\BaseTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\SearchService
 */
class SearchServiceTest extends TestCase
{
    private $victim;
    private $searchRepository;

    protected function setUp(): void
    {
        $this->searchRepository = $this->createMock(SearchRepository::class);
    }

    /**
     * @dataProvider searchProductDataProvider
     *
     * @covers ::getSearchProduct
     * @covers ::handleResponse
     * @covers ::__construct
     * @covers \App\Transformer\BaseTransformer::apply
     * @covers \App\Transformer\BaseTransformer::getStatusCode
     * @covers \App\Transformer\BaseTransformer::getContentType
     * @covers \App\Transformer\BaseTransformer::setContentType
     */
    public function testSearch(array $repoParams = [], array $repoResponse = [])
    {
        $this->victim = new SearchService($this->searchRepository);
        $this->assertInstanceOf(SearchService::class, $this->victim);

        $transformer = new BaseTransformer();
        $transformer->setContentType("application/json");
        $expected = $transformer->apply(new SearchProductResponseModel($repoResponse));

        $this->searchRepository->expects($this->once())
            ->method('getSearchProduct')
            ->with($repoParams)
            ->willReturn($repoResponse);

        $result = $this->victim->getSearchProduct($repoParams);

        $this->assertEquals($expected->getContent(), $result->getContent());
    }

    public function searchProductDataProvider(): array
    {
        $ramCollection = new RamCollectionModel();
        $ramCollection->append(new RamModel(['value' => '4GB']));
        $ramCollection->append(new RamModel(['value' => '8GB']));

        return [
            'repoParams' => [
                [
                    new StorageModel(
                        [
                            'start' => '250GB',
                            'end' => '8TB',
                        ]
                    ),
                    $ramCollection,
                    'hdd' => 'SATA',
                    'location' => 'AmsterdamAMS-01',
                ]
            ],
            'repoResponse' => [
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
                    ],
                    [
                        "productId" => 10,
                        "model" => "HP DL120G7Intel G850",
                        "ram" => "8GBDDR3",
                        "hdd" => "4x1TBSATA2",
                        "location" => "AmsterdamAMS-01",
                        "price" => 40.99,
                        "currency" => "EUR",
                        "currencySymbol" => "€",
                        "discType" => "SATA",
                        "memorySize" => "4",
                        "memoryScale" => "GB",
                        "discSize" => "4",
                        "discScale" => "TB"
                    ],
                ],
            ]
        ];
    }
}
