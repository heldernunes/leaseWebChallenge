<?php

namespace App\DAO;

use App\Model\MigrationProductModel;
use Doctrine\DBAL\ParameterType;

/**
 * @codeCoverageIgnore
 */
class MigrationBaseDao extends BaseDao
{
    public function __construct(array $dbConfig)
    {
        parent::__construct($dbConfig['php']);
    }

    public function insertProduct(array $product): bool
    {
        $discTypeId = $this->fetchDiscTypeId($product['discType']);
        $memoryId = $this->fetchMemoryId($product['memorySize'], $product['memoryScale']);
        $currencyId = $this->fetchCurrencyId($product['currency']);
        $storageId = $this->fetchStorageId($product['discSize'], $product['discScale']);

        $sql = 'INSERT INTO `Product` (model, ram, hdd, location, price, memoryId, discTypeId, storageId, currencyId) 
                VALUES (:model, :ram, :hdd, :location, :price, :memoryId, :discTypeId, :storageId, :currencyId)';
        $params = [
            'model' => $product['model'],
            'ram' => $product['ram'],
            'hdd' => $product['hdd'],
            'location' => $product['location'],
            'price' => $product['price'],
            'memoryId' => $memoryId,
            'discTypeId' => $discTypeId,
            'storageId' => $storageId,
            'currencyId' => $currencyId,
        ];

        try {
            $result =  $this->execute($sql, $params);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function fetchDiscTypeId(string $discType): int
    {
        $sql = 'SELECT discTypeId FROM `DiscType` WHERE type = :discType';
        $params = ['discType' => $discType];
        $types = ['discType' => ParameterType::STRING];

        $result = $this->fetchOne($sql, $params, $types);

        if (false === $result) {
            $insert = 'INSERT INTO DiscType (type) VALUES (:discType)';
            $this->execute($insert, $params, $types);
            return $this->fetchDiscTypeId($discType);
        }

        return (int) $result;
    }

    public function fetchMemoryId(int $memorySize, string $memoryScale): int
    {
        $sql = 'SELECT memoryId FROM `Memory` WHERE size = :memorySize and scale = :memoryScale';
        $params = ['memorySize' => $memorySize, 'memoryScale' => $memoryScale];
        $types = ['memorySize' => ParameterType::INTEGER, 'memoryScale' => ParameterType::STRING];

        $result = $this->fetchOne($sql, $params, $types);

        if (false === $result) {
            $insert = 'INSERT INTO Memory (size, scale) VALUES (:memorySize, :memoryScale)';
            $this->execute($insert, $params, $types);
            return $this->fetchMemoryId($memorySize, $memoryScale);
        }

        return (int) $result;
    }

    public function fetchCurrencyId(string $currency): int
    {
        $sql = 'SELECT currencyId FROM `Currency` WHERE symbol = :symbol';
        $params = ['symbol' => $currency];
        $types = ['symbol' => ParameterType::STRING];

        return (int) $this->fetchOne($sql, $params, $types);
    }


    public function fetchStorageId(int $discSize, string $discScale): int
    {
        $sql = 'SELECT storageId FROM `Storage` WHERE size = :storageSize and scale = :storageScale';
        $params = ['storageSize' => $discSize, 'storageScale' => $discScale];
        $types = ['storageSize' => ParameterType::INTEGER, 'storageScale' => ParameterType::STRING];

        $result = $this->fetchOne($sql, $params, $types);

        if (false === $result) {
            $insert = 'INSERT INTO Memory (size, scale) VALUES (:storageSize, :storageScale)';
            $this->execute($insert, $params, $types);
            return $this->fetchStorageId($discSize, $discScale);
        }

        return (int) $result;
    }

    public function setMigrated(string $file)
    {
        $insert = 'INSERT INTO Migrated (file) VALUES (:file)';

        return $this->execute($insert, ['file' => $file], ['file' => ParameterType::STRING]);
    }

    public function isMigrated(string $file): bool
    {
        $sql = 'SELECT * FROM Migrated WHERE file = :file';

        return (bool) $this->fetchOne($sql, ['file' => $file], ['file' => ParameterType::STRING]);
    }
}
