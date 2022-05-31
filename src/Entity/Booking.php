<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_begin;

    /**
     * @ORM\Column(type="date")
     */
    private $date_end;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="key_booking_as_booker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_booker_user_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="key_booking_as_owner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_house_owner_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKeyBookerUserId(): ?User
    {
        return $this->key_booker_user_id;
    }

    public function setKeyBookerUserId(?User $key_booker_user_id): self
    {
        $this->key_booker_user_id = $key_booker_user_id;

        return $this;
    }

    public function getKeyHouseOwnerId(): ?User
    {
        return $this->key_house_owner_id;
    }

    public function setKeyHouseOwnerId(?User $key_house_owner_id): self
    {
        $this->key_house_owner_id = $key_house_owner_id;

        return $this;
    }
}
