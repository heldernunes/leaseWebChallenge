<?php

namespace App\Service;

use App\DAO\MigrationBaseDao;
use App\Helper\CriteriaHelper;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\MigrationService
 */
class MigrationServiceTest extends TestCase
{
    protected $migrationDao;
    protected $criteriaHelper;
    protected $projectDir;

    protected function setUp()
    {
        $this->migrationDao = $this->createMock(MigrationBaseDao::class);
        $this->criteriaHelper = new CriteriaHelper();
        $this->projectDir = getcwd() . '/tests';
    }

    /**
     * @dataProvider providerForMigrationService
     * @covers ::__construct
     * @covers ::migrateCsv
     * @covers ::convertCsvToArray
     * @covers \App\Helper\CriteriaHelper::hddBreakdown
     * @covers \App\Helper\CriteriaHelper::checkHddType
     * @covers \App\Helper\CriteriaHelper::generateSize
     * @covers \App\Helper\CriteriaHelper::priceBreakdown
     * @covers \App\Helper\CriteriaHelper::ramBreakdown
     */
    public function testMigrateCsv(bool $isMigrated, array $products)
    {
        $this->victim = new MigrationService($this->migrationDao, $this->projectDir, $this->criteriaHelper);
        $this->assertInstanceOf(MigrationService::class, $this->victim);

        $this->migrationDao->expects($this->once())
            ->method('isMigrated')
            ->willReturn($isMigrated);

        if (!$isMigrated) {
            $this->migrationDao->expects($this->exactly(3))
                ->method('insertProduct');

            $this->migrationDao->expects($this->once())
                ->method('setMigrated');
        }

        $result = $this->victim->migrateCsv();

        $this->assertEquals($products, $result);

    }

    public function providerForMigrationService(): array
    {
        return [
            [
                'isMigrated' => false,
                'products' => $this->getProducts(),
            ],
            [
                'isMigrated' => true,
                'products' => $this->getProducts(),
            ],
        ];
    }

    protected function getProducts(): array
    {
        return [
            [
                'Model' => 'Dell test server',
                'RAM' => '16GBDDR3',
                'HDD' => '2x2TBSATA2',
                'Location' => 'location',
                'Price' => '€49.99',
            ],
            [
                'Model' => 'HP test server',
                'RAM' => '32GBDDR3',
                'HDD' => '2x250GBSSD',
                'Location' => 'location',
                'Price' => '€119.00',
            ],
            [
                'Model' => 'HP test server',
                'RAM' => '32GBDDR3',
                'HDD' => '8x2TBSAS',
                'Location' => 'location',
                'Price' => '€131.99',
            ],
        ];
    }

}