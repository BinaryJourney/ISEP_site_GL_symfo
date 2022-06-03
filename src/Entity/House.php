<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HouseRepository::class)
 */
class House
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date_begin;

    /**
     * @ORM\Column(type="date")
     */
    private $date_end;

    /**
     * @ORM\ManyToOne(targetEntity=TypeAccommodationCapacity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_type_accommodation_capacity;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $image_filename;

    /**
     * @ORM\ManyToOne(targetEntity=ListeVilleFrance::class, inversedBy="key_house")
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_liste_ville_france;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="key_houses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_user;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="key_house", orphanRemoval=true)
     */
    private $key_bookings;

    public function __construct()
    {
        $this->key_bookings = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->date_begin;
    }

    public function setDateBegin(\DateTimeInterface $date_begin): self
    {
        $this->date_begin = $date_begin;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getKeyTypeAccommodationCapacity(): ?TypeAccommodationCapacity
    {
        return $this->key_type_accommodation_capacity;
    }

    public function setKeyTypeAccommodationCapacity(?TypeAccommodationCapacity $key_type_accommodation_capacity): self
    {
        $this->key_type_accommodation_capacity = $key_type_accommodation_capacity;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->image_filename;
    }

    public function setImageFilename(string $image_filename): self
    {
        $this->image_filename = $image_filename;

        return $this;
    }

    public function getKeyListeVilleFrance(): ?ListeVilleFrance
    {
        return $this->key_liste_ville_france;
    }

    public function setKeyListeVilleFrance(?ListeVilleFrance $key_liste_ville_france): self
    {
        $this->key_liste_ville_france = $key_liste_ville_france;

        return $this;
    }

    public function getKeyUser(): ?User
    {
        return $this->key_user;
    }

    public function setKeyUser(?User $key_user): self
    {
        $this->key_user = $key_user;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getKeyBookings(): Collection
    {
        return $this->key_bookings;
    }

    public function addKeyBooking(Booking $keyBooking): self
    {
        if (!$this->key_bookings->contains($keyBooking)) {
            $this->key_bookings[] = $keyBooking;
            $keyBooking->setKeyHouse($this);
        }

        return $this;
    }

    public function removeKeyBooking(Booking $keyBooking): self
    {
        if ($this->key_bookings->removeElement($keyBooking)) {
            // set the owning side to null (unless already changed)
            if ($keyBooking->getKeyHouse() === $this) {
                $keyBooking->setKeyHouse(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }


}
