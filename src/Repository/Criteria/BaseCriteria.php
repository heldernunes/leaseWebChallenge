<?php

namespace App\Repository\Criteria;

use App\Helper\CriteriaHelper;

class BaseCriteria implements CriteriaInterface
{
    protected static array $conditions;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getConditions(): array
    {
        return self::$conditions;
    }

    public function addConditions(string $condition): void
    {
        self::$conditions[] = $condition;
    }

    /**
     * @codeCoverageIgnore
     */
    public function createCriteriaQuery(): void
    {
    }

    protected function scaleBreakdown(string $hdd): array
    {
        switch (true) {
            case (stripos($hdd, CriteriaHelper::GB_SCALE) !== false):
                $hddData = explode(CriteriaHelper::GB_SCALE, $hdd);
                $hddBreakDown = [
                    'scale' => CriteriaHelper::GB_SCALE,
                    'size' => $hddData[0],
                ];

                break;
            case (stripos($hdd, CriteriaHelper::TB_SCALE) !== false):
            default:
                $hddData = explode(CriteriaHelper::TB_SCALE, $hdd);
                $hddBreakDown = [
                    'scale' => CriteriaHelper::TB_SCALE,
                    'size' => $hddData[0],
                ];

                break;
        }

        return $hddBreakDown;
    }
}
