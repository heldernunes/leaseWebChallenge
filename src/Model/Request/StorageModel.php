<?php

namespace App\Model\Request;

/**
 * @codeCoverageIgnore
 */
class StorageModel extends BaseReqModel
{
    /**
     * @Assert\Type ("String#, message="It must be a string.")
     *
     * @var string
     */
    protected $start;

    /**
     * @Assert\Type ("String#, message="It must be a string.")
     *
     * @var string
     */
    protected $end;

    public function getStart(): string
    {
        return $this->start;
    }

    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    public function setEnd(string $end): void
    {
        $this->end = $end;
    }
}
