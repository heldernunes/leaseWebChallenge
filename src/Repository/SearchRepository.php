<?php

namespace App\Repository;

use App\DAO\SearchDao;
use App\Repository\Criteria\CriteriaInterface;
use App\Repository\Criteria\HddCriteria;
use App\Repository\Criteria\StorageCriteria;
use App\Repository\Criteria\RamCriteria;
use App\Repository\Criteria\LocationCriteria;

class SearchRepository
{
    protected $searchDao;

    public function __construct(SearchDao $searchDao)
    {
        $this->searchDao = $searchDao;
    }

    public function getSearchProduct(array $params): array
    {
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $criteriaObject = $this->addCriteria($key, $value);
            }
        }

        return $this->searchDao->getSearchProduct($criteriaObject->getConditions());
    }

    protected function addCriteria($criteria, $values): CriteriaInterface
    {
        $criteriaName = sprintf(
            "%s\\Criteria\\%s",
            __NAMESPACE__,
            ucfirst($criteria) . 'Criteria'
        );

        $criteriaObject = new  $criteriaName($values);
        $criteriaObject->createCriteriaQuery();

        return $criteriaObject;
    }
}