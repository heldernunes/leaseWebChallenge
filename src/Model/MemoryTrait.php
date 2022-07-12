<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
trait MemoryTrait
{
    /**
     * @var string
     */
    protected $memoryScale;

    /**
     * @var int
     */
    protected $memorySize;


    /**
     * @var string
     */
    protected $memoryType;

    /**
     * @return string
     */
    public function getMemoryScale(): string
    {
        return $this->memoryScale;
    }

    public function setMemoryScale(string $memoryScale): void
    {
        $this->memoryScale = $memoryScale;
    }

    public function getMemorySize(): int
    {
        return $this->memorySize;
    }

    public function setMemorySize(int $memorySize): void
    {
        $this->memorySize = $memorySize;
    }

    public function getMemoryType(): string
    {
        return $this->memoryType;
    }

    public function setMemoryType(string $memoryType): void
    {
        $this->memoryType = $memoryType;
    }
}
