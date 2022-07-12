<?php

namespace App\Model\Request;

use \ArrayIterator;

/**
 * @codeCoverageIgnore
 */
class RamCollectionModel extends ArrayIterator
{
    public function __construct(RamModel ...$ramPrices)
    {
        parent::__construct($ramPrices);
    }

    public function current(): RamModel
    {
        return parent::current();
    }

    public function offsetGet($offset): RamModel
    {
        return parent::offsetGet($offset);
    }

    public function toArray(): array
    {
        return array_map(
            function ($ramModel) {
                /* @var $ramModel RamModel */
                return $ramModel->toArray();
            },
            array_values(iterator_to_array($this))
        );
    }
}