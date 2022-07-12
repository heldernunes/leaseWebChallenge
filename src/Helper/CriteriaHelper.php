<?php

namespace App\Helper;

class CriteriaHelper
{
    const TB_SCALE = 'TB';
    const GB_SCALE = 'GB';
    const HDD_TYPE_SAS = 'SAS';
    const HDD_TYPE_SATA = 'SATA';
    const HDD_TYPE_SSD = 'SSD';

    public function priceBreakdown(string $price)
    {
        $value = preg_replace('/([^0-9\.,])/i', '', $price);

        return [
            'value' => (float) $value,
            'currency' => trim($price, $value),
        ];
    }

    public function hddBreakdown(string $hdd): array
    {
        $hddBreakDown = [];
        switch (true) {
            case (stripos($hdd, self::GB_SCALE) !== false):
                $hddData = explode(self::GB_SCALE, $hdd);
                $hddBreakDown = [
                    'hddScale' => self::GB_SCALE,
                    'hddSize' => $this->generateSize($hddData[0]),
                    'hddType' => $this->checkHddType($hddData[1])
                ];

                break;
            case (stripos($hdd, self::TB_SCALE) !== false):
            default:
                $hddData = explode(self::TB_SCALE, $hdd);
                $hddBreakDown = [
                    'hddScale' => self::TB_SCALE,
                    'hddSize' => $this->generateSize($hddData[0]),
                    'hddType' => $this->checkHddType($hddData[1])
                ];

                break;
        }

        return $hddBreakDown;
    }

    public function ramBreakdown(string $ram): array
    {
        $ramData = explode(self::GB_SCALE, $ram);
        return [
            'ramScale' => self::GB_SCALE,
            'ramSize' => $ramData[0],
            'ramType' => $ramData[1],
        ];
    }

    protected function generateSize(string $value): int
    {
        $values = explode('x', strtolower($value));

        return is_array($values) ? $values[0] * $values[1] : 0;
    }

    protected function checkHddType($hddData): string
    {
        switch (true) {
            case (stripos($hddData, self::HDD_TYPE_SATA) !== false):
                $hddType = self::HDD_TYPE_SATA;
                break;
            case (stripos($hddData, self::HDD_TYPE_SAS) !== false):
                $hddType = self::HDD_TYPE_SAS;
                break;
            case (stripos($hddData, self::HDD_TYPE_SSD) !== false):
            default:
                $hddType = self::HDD_TYPE_SSD;
                break;
        }
        return $hddType;
    }
}
