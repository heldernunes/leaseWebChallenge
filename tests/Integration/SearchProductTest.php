<?php
namespace Tests\Integration;

use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchProductTest extends KernelTestCase
{
    protected Client $connection;

    protected $dbConnection;
    protected $product;

    public function setUp()
    {
        parent::setUp();

        self::bootKernel();
        $container = self::getContainer();
        $dbConfig = $container->getParameter('databases');
        $this->dbConnection = DriverManager::getConnection($dbConfig['php']);

        $this->connection = new Client([
            'verify' => false,
            'base_uri' => 'http://nginx',
        ]);

        $sql = 'INSERT INTO `Product` (model, ram, hdd, location, price, memoryId, discTypeId, storageId, currencyId) 
                VALUES (:model, :ram, :hdd, :location, :price, :memoryId, :discTypeId, :storageId, :currencyId)';
        $params = [
            'model' => 'test server',
            'ram' => '24GBDDR3',
            'hdd' => '2x250GBSATA2',
            'location' => 'location',
            'price' => 49.99,
            'memoryId' => 6,
            'discTypeId' => 2,
            'storageId' => 2,
            'currencyId' => 1,
        ];

        $this->dbConnection->executeQuery($sql, $params);

        $this->product = $this->dbConnection->fetchAllAssociative(
            "SELECT * From `Product` WHERE `model` = 'test server'"
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->dbConnection->executeQuery(
            "DELETE FROM `Product` WHERE `productId` = :productId",
            ['productId' => $this->product[0]['productId']]
        );
    }

    public function testSearchProduct()
    {
        $options['body'] = '{
            "storage": [
                {
                    "start": "250GB",
                    "end": "8TB"
                }
            ], 
            "ram": [
                {"value": "24GB"}
            ],
            "hdd": "SATA",
            "location": "location"
        }';
        $response = $this->connection->get('/search', $options);
        $result = json_decode($response->getBody()->getContents(), true);

        $expected = $this->product[0];
        $searchedValue = $result['data'][0];

        $this->assertTrue($expected['productId'] == $searchedValue['productId']);
        $this->assertEquals($expected['model'], $searchedValue['model']);
        $this->assertTrue($expected['price'] == $searchedValue['price']);
        $this->assertEquals($expected['ram'], $searchedValue['ram']);
        $this->assertEquals($expected['hdd'], $searchedValue['hdd']);
        $this->assertEquals($expected['location'], $searchedValue['location']);
    }
}
