<?php

namespace App\DAO;

/**
 * @codeCoverageIgnore
 */
class SearchDao extends BaseDao
{
    public function getSearchProduct(array $conditions = []): array
    {
        $sql = 'SELECT p.productId, 
                p.model, 
                p.ram, 
                p.hdd, 
                p.location, 
                p.price, 
                c.currency, 
                c.symbol as currencySymbol, 
                dt.type as discType, 
                m.size as memorySize, 
                m.scale as memoryScale, 
                s.size as discSize, 
                s.scale as discScale
                FROM `Product` p
                JOIN `DiscType` dt using(`discTypeId`)
                JOIN `Memory` m using(`memoryId`)
                JOIN `Storage`s  using(`storageId`)
                JOIN `Currency` c using(`currencyId`)';
        if (!empty($conditions)) {
            $sql .= "\n WHERE " . implode(' AND ', $conditions);
        }


        return $this->fetchAll($sql);
    }
}
