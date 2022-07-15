<?php

namespace App\Repository\Criteria;

use App\Model\Request\StorageModel;

class StorageCriteria extends BaseCriteria
{

    public function createCriteriaQuery(): void
    {
        $data = $this->data->toArray();
        $start = $this->scaleBreakdown($data['start']);
        $end = $this->scaleBreakdown($data['end']);
        $sql = "s.`storageId` Between 
        (SELECT storageId FROM Storage WHERE size = {$start['size']} and scale = '{$start['scale']}') 
        AND (SELECT storageId FROM Storage WHERE size = {$end['size']} and scale = '{$end['scale']}')";

        $this->addConditions($sql);
    }
}
