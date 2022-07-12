<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
trait ArrayConversion
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
