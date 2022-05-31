<?php

namespace App\Entity;

use App\Repository\ListeVilleFranceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ListeVilleFranceRepository::class)
 */
class ListeVilleFrance
{
    /**
     * @Groups("main")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=House::class, mappedBy="key_liste_ville_france")
     */
    private $key_house;

    public function __construct()
    {
        $this->key_house = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, House>
     */
    public function getKeyHouse(): Collection
    {
        return $this->key_house;
    }

    public function addKeyHouse(House $keyHouse): self
    {
        if (!$this->key_house->contains($keyHouse)) {
            $this->key_house[] = $keyHouse;
            $keyHouse->setKeyListeVilleFrance($this);
        }

        return $this;
    }

    public function removeKeyHouse(House $keyHouse): self
    {
        if ($this->key_house->removeElement($keyHouse)) {
            // set the owning side to null (unless already changed)
            if ($keyHouse->getKeyListeVilleFrance() === $this) {
                $keyHouse->setKeyListeVilleFrance(null);
            }
        }

        return $this;
    }

    /**
     * @Groups("main")
     */
    public function getNameAndDpt(): ?string
    {
        return $this->getName()." (".$this->getDepartment().")";
    }

    public function __toString(): string
    {
        return $this->getName() . " (" . $this->getDepartment() . ")";
    }

}
