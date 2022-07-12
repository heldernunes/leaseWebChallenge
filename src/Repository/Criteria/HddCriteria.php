<?php

namespace App\Repository\Criteria;

class HddCriteria extends BaseCriteria
{
    public function createCriteriaQuery(): void
    {
        $this->addConditions("dt.type = '{$this->data}'");
    }
}
