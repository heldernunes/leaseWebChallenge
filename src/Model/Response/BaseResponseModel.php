<?php

namespace App\Model\Response;

use App\Model\ArrayConversion;

/**
 * @codeCoverageIgnore
 */
class BaseResponseModel implements ResponseModelInterface
{
    use ArrayConversion;

    /**
     * @Assert\NotNull()
     * @Assert\Type("bool")
     *
     * @var bool
     */
    protected $success;

    /**
     * @Assert\Type("ResponseModelInterface")
     *
     * @var ResponseModelInterface
     */
    protected $data;

    /**
     * @Assert\Type("ErrorResponseModel")
     *
     * @var ErrorResponseModel
     */
    protected $message;

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success): BaseResponseModel
    {
        $this->success = $success;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): BaseResponseModel
    {
        $this->data = $data;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): BaseResponseModel
    {
        $this->message = $message;

        return $this;
    }
}
