<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

class ItemDTO
{
    #[Assert\NotBlank(message: 'Name cannot be empty')]
    #[Assert\Length(max: 255, maxMessage: 'Name cannot be longer than {{ limit }} characters')]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'Price cannot be empty')]
    #[Assert\Positive(message: 'Price must be a positive number')]
    #[Assert\Type(type: 'float', message: 'Price must be a valid monetary value')]
    private ?float $price = null;

    #[Assert\NotBlank(message: 'Date and time cannot be empty')]
    private ?DateTimeInterface $dateTime = null;

    // Raw date time string for validation purposes
    #[Assert\NotBlank(message: 'Date and time cannot be empty')]
    #[Assert\Regex(
        pattern: '/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/',
        message: 'Date and time must be in format dd.mm.yyyy hh:mm:ss'
    )]
    private ?string $rawDateTime = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getDateTime(): ?DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(?DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getRawDateTime(): ?string
    {
        return $this->rawDateTime;
    }

    public function setRawDateTime(?string $rawDateTime): self
    {
        $this->rawDateTime = $rawDateTime;
        return $this;
    }
}
