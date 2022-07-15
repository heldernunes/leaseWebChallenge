<?php

namespace App\Model\Request;

/**
 * @codeCoverageIgnore
 */
class RamModel extends BaseReqModel
{
    protected $value;

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }
}
