<?php

namespace App\Service;

use App\DAO\MigrationBaseDao;
use App\Helper\CriteriaHelper;
use App\Model\MigrationProductModel;
use App\Model\Response\BaseResponseModel;

class MigrationService
{
    protected $migrationBaseDao;
    protected $projectDir;
    protected $migrationHelper;

    public function __construct(
        MigrationBaseDao $migrationBaseDao,
        string $projectDir,
        CriteriaHelper $migrationHelper
    ) {
        $this->migrationBaseDao = $migrationBaseDao;
        $this->projectDir = $projectDir;
        $this->migrationHelper = $migrationHelper;
    }

    public function migrateCsv()
    {
        list($products, $files) = $this->convertCsvToArray();
        foreach ($files as $file) {
            if (!$this->migrationBaseDao->isMigrated($file)) {
                foreach ($products as $product) {
                    $migrationProductModel = new MigrationProductModel();
                    $migrationProductModel->setModel($product['Model']);
                    $migrationProductModel->setLocation($product['Location']);
                    $migrationProductModel->setHdd($product['HDD']);
                    $migrationProductModel->setRam($product['RAM']);
                    $hddData = $this->migrationHelper->hddBreakdown($product['HDD']);
                    $priceData = $this->migrationHelper->priceBreakdown($product['Price']);
                    $ramData = $this->migrationHelper->ramBreakdown($product['RAM']);
                    $migrationProductModel->setPrice($priceData['value']);
                    $migrationProductModel->setDiscScale($hddData['hddScale']);
                    $migrationProductModel->setDiscSize($hddData['hddSize']);
                    $migrationProductModel->setDiscType($hddData['hddType']);
                    $migrationProductModel->setMemorySize($ramData['ramSize']);
                    $migrationProductModel->setMemoryScale($ramData['ramScale']);
                    $migrationProductModel->setMemoryType($ramData['ramType']);
                    $migrationProductModel->setCurrency($priceData['currency']);
                    $migrationProductModel->setValue($priceData['value']);

                    $this->migrationBaseDao->insertProduct($migrationProductModel->toArray());
                }
                $this->migrationBaseDao->setMigrated($file);
            }
        }

        return $products;
    }

    protected function convertCsvToArray(): array
    {
        $files = glob($this->projectDir . "/assets/*.csv");
        $assoc_array = [];
        foreach ($files as $filepath) {
            if ($handle = fopen($filepath, "r")) {
                if (($handle = fopen($filepath, "r")) !== false) {
                    if (($data = fgetcsv($handle, 1000, ";")) !== false) {
                        $data[0] = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $data[0]);
                        $keys = $data;
                    }
                    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                        $assoc_array[] = array_combine($keys, $data);
                    }
                    fclose($handle);
                }
            }
        }

        return [$assoc_array, $files];
    }
}
