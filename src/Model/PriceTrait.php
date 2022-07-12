<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
trait PriceTrait
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $value;

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}
