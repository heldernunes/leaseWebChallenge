<?php

namespace App\Tests\Unit\Repository;

use App\DAO\SearchDao;
use App\Model\Request\RamCollectionModel;
use App\Model\Request\RamModel;
use App\Model\Request\StorageModel;
use App\Repository\SearchRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Repository\SearchRepository
 */
class SearchRepositoryTest extends TestCase
{
    private $victim;
    private $searchDao;

    protected function setUp(): void
    {
        $this->searchDao = $this->createMock(SearchDao::class);
    }

    /**
     * @dataProvider searchProductDataProvider
     * @covers ::__construct
     * @covers ::getSearchProduct
     * @covers ::addCriteria
     * @covers \App\Repository\Criteria\BaseCriteria::__construct()
     * @covers \App\Repository\Criteria\BaseCriteria::getConditions()
     * @covers \App\Repository\Criteria\BaseCriteria::addConditions
     * @covers \App\Repository\Criteria\BaseCriteria::scaleBreakdown
     * @covers \App\Repository\Criteria\StorageCriteria::createCriteriaQuery()
     * @covers \App\Repository\Criteria\RamCriteria::createCriteriaQuery
     * @covers \App\Repository\Criteria\LocationCriteria::createCriteriaQuery
     * @covers \App\Repository\Criteria\HddCriteria::createCriteriaQuery
     */
    public function testGetSearchProduct(array $repoParams, array $daoResponse)
    {
        $this->victim = new SearchRepository($this->searchDao);
        $this->assertInstanceOf(SearchRepository::class, $this->victim);

        $this->searchDao->expects($this->once())
            ->method('getSearchProduct')
            ->willReturn($daoResponse);

        $results = $this->victim->getSearchProduct($repoParams[0]);
        $this->assertEquals($daoResponse, $results);
    }

    public function searchProductDataProvider(): array
    {
        $ramCollection = new RamCollectionModel();
        $ramCollection->append(new RamModel(['value' => '4GB']));
        $ramCollection->append(new RamModel(['value' => '8GB']));

        return [
            [
                'repoParams' => [
                    [
                        'storage' => new StorageModel(
                            [
                                'start' => '250GB',
                                'end' => '8TB',
                            ]
                        ),
                        'ram' => $ramCollection,
                        'hdd' => 'SATA',
                        'location' => 'AmsterdamAMS-01',
                    ]
                ],
                'daoResponse' => [
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
                    ],
                ]
            ]
        ];
    }
}
