<?php

namespace App\Model\Response;

/**
 * @codeCoverageIgnore
 */
class ErrorResponseModel implements ResponseModelInterface
{
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     * @Assert\Type("int")
     *
     * @var int
     */
    protected $code;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     * @Assert\Type("string")
     *
     * @var string
     */
    protected $description;

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
