<?php

namespace App\Entity;

use App\Repository\TypeAccommodationCapacityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeAccommodationCapacityRepository::class)
 */
class TypeAccommodationCapacity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $capacity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function __toString()
    {
        return $this->capacity;
    }


}
