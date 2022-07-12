<?php

namespace App\Model;

/**
 * @codeCoverageIgnore
 */
class ProductModel
{
    use ArrayConversion;

    protected int $productId;

    protected string $model;

    protected string $ram;

    protected string $hdd;

    protected string $location;

    protected float $price;

    protected string $currency;

    protected string $currencySymbol;

    protected string $discType;

    protected string $memorySize;

    protected string $memoryScale;

    protected string $discSize;

    protected string $discScale;

    public function __construct(array $values)
    {
        $this->productId = $values['productId'];
        $this->model = $values['model'];
        $this->ram = $values['ram'];
        $this->hdd = $values['hdd'];
        $this->location = $values['location'];
        $this->price = $values['price'];
        $this->currency = $values['currency'];
        $this->currencySymbol = $values['currencySymbol'];
        $this->discType = $values['discType'];
        $this->memorySize = $values['memorySize'];
        $this->memoryScale = $values['memoryScale'];
        $this->discSize = $values['discSize'];
        $this->discScale = $values['discScale'];
    }
}
