<?php

namespace App\Repository\Criteria;

interface CriteriaInterface
{
    public function getConditions(): array;

    public function addConditions(string $condition): void;

    public function createCriteriaQuery(): void;
}
