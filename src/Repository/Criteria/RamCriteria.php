<?php

namespace App\Repository\Criteria;

use App\Model\Request\RamCollectionModel;
use App\Model\Request\RamModel;

class RamCriteria extends BaseCriteria
{
    public function createCriteriaQuery(): void
    {
        $data = $this->data->toArray();
        $searchValues = [];
        foreach ($data as $ramModel) {
            $values = $this->scaleBreakdown($ramModel['value']);
            $searchValues[] = $values['size'];
        }
        $inValues = implode(",", $searchValues);
        $sql = "m.size IN ({$inValues})";

        $this->addConditions($sql);
    }
}
