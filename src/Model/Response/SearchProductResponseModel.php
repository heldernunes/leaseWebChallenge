<?php

namespace App\Model\Response;

use App\Model\ProductModel;
use \ArrayIterator;

/**
 * @codeCoverageIgnore
 */
class SearchProductResponseModel extends ArrayIterator implements ResponseModelInterface
{
    public function __construct(array $productList)
    {
        foreach ($productList as $product) {
            $this->append(new ProductModel($product));
        }
    }

    public function toArray(): array
    {
        return array_map(
            function ($productModel) {
                /** @var $productModel ProductModel */
                return $productModel->toArray();
            },
            array_values(iterator_to_array($this))
        );
    }
}
