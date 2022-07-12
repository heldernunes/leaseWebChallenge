<?php

namespace App\Model\Request;

use App\Model\ArrayConversion;
use Throwable;

/**
 * @codeCoverageIgnore
 */
class BaseReqModel implements RequestModelInterface
{
    use ArrayConversion;

    public function __construct(array $data = null)
    {
        if (is_array($data) || (is_object($data))) {
            foreach ($data as $field => $value) {
                try {
                    $methodName = 'set' . ucfirst($field);
                    if (method_exists($this, $methodName) && !is_null($value)) {
                        $this->$methodName($value);
                    }
                } catch (Throwable $exception) { //phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
                    //this will be thrown when the user insert a wrong input
                    //that error will be catch by the validation on the next step
                }
            }
        }
    }
}
