<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\ManyToOne(targetEntity=TypeSex::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $key_type_sex;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="key_booker_user_id", orphanRemoval=true)
     */
    private $key_booking_as_booker;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="key_house_owner_id", orphanRemoval=true)
     */
    private $key_booking_as_owner;

    /**
     * @ORM\OneToMany(targetEntity=House::class, mappedBy="key_user", orphanRemoval=true)
     */
    private $key_houses;

    public function __construct()
    {
        $this->key_house_owner_user_id = new ArrayCollection();
        $this->key_booking_as_booker = new ArrayCollection();
        $this->key_booking_as_owner = new ArrayCollection();
        $this->key_houses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getKeyTypeSex(): ?TypeSex
    {
        return $this->key_type_sex;
    }

    public function setKeyTypeSex(?TypeSex $key_type_sex): self
    {
        $this->key_type_sex = $key_type_sex;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getKeyBookingAsBooker(): Collection
    {
        return $this->key_booking_as_booker;
    }

    public function addKeyBookingAsBooker(Booking $keyBookingAsBooker): self
    {
        if (!$this->key_booking_as_booker->contains($keyBookingAsBooker)) {
            $this->key_booking_as_booker[] = $keyBookingAsBooker;
            $keyBookingAsBooker->setKeyBookerUserId($this);
        }

        return $this;
    }

    public function removeKeyBookingAsBooker(Booking $keyBookingAsBooker): self
    {
        if ($this->key_booking_as_booker->removeElement($keyBookingAsBooker)) {
            // set the owning side to null (unless already changed)
            if ($keyBookingAsBooker->getKeyBookerUserId() === $this) {
                $keyBookingAsBooker->setKeyBookerUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getKeyBookingAsOwner(): Collection
    {
        return $this->key_booking_as_owner;
    }

    public function addKeyBookingAsOwner(Booking $keyBookingAsOwner): self
    {
        if (!$this->key_booking_as_owner->contains($keyBookingAsOwner)) {
            $this->key_booking_as_owner[] = $keyBookingAsOwner;
            $keyBookingAsOwner->setKeyHouseOwnerId($this);
        }

        return $this;
    }

    public function removeKeyBookingAsOwner(Booking $keyBookingAsOwner): self
    {
        if ($this->key_booking_as_owner->removeElement($keyBookingAsOwner)) {
            // set the owning side to null (unless already changed)
            if ($keyBookingAsOwner->getKeyHouseOwnerId() === $this) {
                $keyBookingAsOwner->setKeyHouseOwnerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, House>
     */
    public function getKeyHouses(): Collection
    {
        return $this->key_houses;
    }

    public function addKeyHouse(House $keyHouse): self
    {
        if (!$this->key_houses->contains($keyHouse)) {
            $this->key_houses[] = $keyHouse;
            $keyHouse->setKeyUser($this);
        }

        return $this;
    }

    public function removeKeyHouse(House $keyHouse): self
    {
        if ($this->key_houses->removeElement($keyHouse)) {
            // set the owning side to null (unless already changed)
            if ($keyHouse->getKeyUser() === $this) {
                $keyHouse->setKeyUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName() . ' ' . $this->getSurname();
    }


}
