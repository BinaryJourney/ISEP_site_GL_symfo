<?php

namespace App\Entity;

use App\Repository\TypeBookingStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeBookingStatusRepository::class)
 */
class TypeBookingStatus
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
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="status")
     */
    private $key_bookings;

    public function __construct()
    {
        $this->key_bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $keyBooking->setStatus($this);
        }

        return $this;
    }

    public function removeKeyBooking(Booking $keyBooking): self
    {
        if ($this->key_bookings->removeElement($keyBooking)) {
            // set the owning side to null (unless already changed)
            if ($keyBooking->getStatus() === $this) {
                $keyBooking->setStatus(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getStatus();
    }


}
