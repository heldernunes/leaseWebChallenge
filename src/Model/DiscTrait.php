<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
trait DiscTrait
{
    /**
     * @var string
     */
    protected $discScale;

    /**
     * @var int
     */
    protected $discSize;


    /**
     * @var string
     */
    protected $discType;

    /**
     * @return string
     */
    public function getDiscScale(): string
    {
        return $this->discScale;
    }

    public function setDiscScale(string $discScale): void
    {
        $this->discScale = $discScale;
    }

    public function getDiscSize(): int
    {
        return $this->discSize;
    }

    public function setDiscSize(int $discSize): void
    {
        $this->discSize = $discSize;
    }

    public function getDiscType(): string
    {
        return $this->discType;
    }

    public function setDiscType(string $discType): void
    {
        $this->discType = $discType;
    }
}
