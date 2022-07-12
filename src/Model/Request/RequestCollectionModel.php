<?php

namespace App\Model\Request;

use ArrayIterator;

/**
 * @codeCoverageIgnore
 */
class RequestCollectionModel extends ArrayIterator
{
    public function __construct(RequestModelInterface ...$models)
    {
        parent::__construct($models);
    }

    public function current(): RequestModelInterface
    {
        return parent::current();
    }

    public function offsetGet($offset): RequestModelInterface
    {
        return parent::offsetGet($offset);
    }

    public function keys(): array
    {
        return array_keys(iterator_to_array($this));
    }
}
