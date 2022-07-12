<?php

namespace App\Repository\Criteria;

class LocationCriteria extends BaseCriteria
{
    public function createCriteriaQuery(): void
    {
        $this->addConditions("p.location = '{$this->data}'");
    }
}
