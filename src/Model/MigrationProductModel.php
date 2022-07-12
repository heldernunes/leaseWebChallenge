<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
class MigrationProductModel
{
    use MemoryTrait;
    use DiscTrait;
    use PriceTrait;
    use ArrayConversion;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $ram;

    /**
     * @var string
     */
    protected $hdd;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var float
     */
    protected $price;

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getRam(): string
    {
        return $this->ram;
    }

    public function setRam(string $ram): void
    {
        $this->ram = $ram;
    }

    public function getHdd(): string
    {
        return $this->hdd;
    }

    public function setHdd(string $hdd): void
    {
        $this->hdd = $hdd;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
