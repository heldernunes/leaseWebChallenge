<?php

namespace App\Tests\Unit\Middleware\TestObject;

use App\Model\Request\RequestModelInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DummyRequestModel implements RequestModelInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     *
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=30)
     *
     * @var string
     */
    private $name;

    private $isActive;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): DummyRequestModel
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): DummyRequestModel
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): DummyRequestModel
    {
        $this->isActive = $isActive;

        return $this;
    }
}
